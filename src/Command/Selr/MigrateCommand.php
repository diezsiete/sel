<?php


namespace App\Command\Selr;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Main\Convenio;
use App\Entity\Main\Usuario;
use App\Service\Autoliquidacion\FileManager;
use Doctrine\Common\Annotations\Reader;
use League\Flysystem\FilesystemInterface;
use Sel\RemoteBundle\Service\Api;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MigrateCommand extends TraitableCommand
{
    use ConsoleProgressBar;
    use SelCommandTrait;

    protected static $defaultName = 'selr:migrate';

    private $migratableEntities = [
        'usuario' => Usuario::class,
        'convenio' => Convenio::class,
        'autoliquidacion' => AutoliquidacionEmpleado::class,
    ];

    private $entities;
    /**
     * @var Api
     */
    private $api;
    /**
     * @var FileManager
     */
    private $autoliqFileManager;
    /**
     * @var FilesystemInterface
     */
    private $selrFilesystem;
    /**
     * @var string
     */
    private $empresa;

    public function __construct(
        Reader $annotationReader,
        EventDispatcherInterface $dispatcher,
        Api $api,
        FileManager $autoliqFileManager,
        FilesystemInterface $selrFilesystem,
        string $empresa
    ) {
        parent::__construct($annotationReader, $dispatcher);
        $this->api = $api;
        $this->autoliqFileManager = $autoliqFileManager;
        $this->selrFilesystem = $selrFilesystem;
        $this->empresa = $empresa;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('entity', InputArgument::OPTIONAL|InputArgument::IS_ARRAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        foreach ($this->entities() as $entityName => $entityClass) {
            $customMigrateMethod = $entityName . 'Migrate';
            if (method_exists($this, $customMigrateMethod)) {
                $this->$customMigrateMethod();
            } else {
                $this->entitiesMigrate($entityName, $entityClass);
            }
        }
    }

    private function convenioMigrate()
    {
        $this->entitiesMigrate('convenio', Convenio::class, ['groups' => 'selr:migrate']);
    }

    private function entitiesMigrate($entityName, $entityClass, $params = null)
    {
        $q = $this->em->createQuery('select x from ' . $entityClass . '  x');
        $iterableResult = $q->iterate();
        foreach ($iterableResult as $row) {
            $this->api->migrate->$entityName->migrate($row[0], $params);
            $this->em->detach($row[0]);
            $this->progressBarAdvance();
        }
    }

    private function autoliquidacionMigrate()
    {
        /** @var Autoliquidacion $autoliquidacion */
        foreach ($this->em->getRepository(Autoliquidacion::class)->findAll() as $autoliquidacion) {
            $this->api->migrate->autoliquidacion->migrate($autoliquidacion, ['groups' => 'selr:migrate']);
            $periodo = $autoliquidacion->getPeriodo();
            foreach ($autoliquidacion->getEmpleados() as $autoliquidacionEmpleado) {
                $ident = $autoliquidacionEmpleado->getUsuario()->getIdentificacion();
                $autoliquidacionEmpleado->hasFile = $this->autoliqFileManager->fileExists($periodo, $ident);

                if ($autoliquidacionEmpleado->hasFile) {
                    $path = $this->empresa . $this->autoliqFileManager->getPath($periodo, $ident);
                    if (!$this->selrFilesystem->has($path)) {
                        $this->selrFilesystem->writeStream($path, $this->autoliqFileManager->readStream($periodo, $ident));
                    }
                }
                $this->api->migrate->autoliquidacion->migrateUsuario($autoliquidacionEmpleado, ['groups' => 'selr:migrate']);
                $this->progressBarAdvance();
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return array_reduce($this->entities(), function ($carry, $entity) {
            return $carry + $this->em->getRepository($entity)->count([]);
        }, 0);
    }



    private function entities()
    {
        if ($this->entities === null) {
            $inputEntities = $this->input->getArgument('entity');
            $this->entities = $inputEntities
                ? array_combine($inputEntities, $this->entities = array_map(function($entity) {
                    return $this->migratableEntities[$entity];
                }, $inputEntities))
                : $this->migratableEntities;
        }
        return $this->entities;
    }
}