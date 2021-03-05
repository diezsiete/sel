<?php

namespace App\Command\Selr;

use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Hv\Hv;
use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use App\Entity\Main\Representante;
use App\Entity\Main\Usuario;
use App\Service\Autoliquidacion\FileManager;
use App\Service\UploaderHelper;
use Doctrine\Common\Annotations\Reader;
use League\Flysystem\FilesystemInterface;
use Sel\RemoteBundle\Dto\UsuarioDto;
use Sel\RemoteBundle\Service\Api;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class MigrateCommand extends TraitableCommand
{
    use ConsoleProgressBar;
    use SelCommandTrait;

    protected static $defaultName = 'selr:migrate';

    private $migratableEntities = [
        'usuario' => Usuario::class,
        'convenio' => Convenio::class,
        'autoliquidacion' => AutoliquidacionEmpleado::class,
        'progreso' => Progreso::class,
        'hv' => Hv::class,
        'representante' => Representante::class
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
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    public function __construct(
        Reader $annotationReader,
        EventDispatcherInterface $dispatcher,
        Api $api,
        FileManager $autoliqFileManager,
        UploaderHelper $uploaderHelper,
        FilesystemInterface $selrFilesystem,
        PropertyAccessorInterface $accessor,
        string $empresa
    ) {
        parent::__construct($annotationReader, $dispatcher);
        $this->api = $api;
        $this->autoliqFileManager = $autoliqFileManager;
        $this->selrFilesystem = $selrFilesystem;
        $this->empresa = $empresa;
        $this->uploaderHelper = $uploaderHelper;
        $this->accessor = $accessor;
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
                $this->entitiesMigrate($entityName, $entityClass, ['groups' => 'selr:migrate']);
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

    private function usuarioMigrate()
    {
        $q = $this->em->createQuery('select u from ' . Usuario::class . ' u');
        $iterableResult = $q->iterate();
        foreach ($iterableResult as $row) {
            $usuarioDto = new UsuarioDto();
            foreach (array_keys(get_object_vars($usuarioDto)) as $propertyName) {
                if ($this->accessor->isReadable($row[0], $propertyName)) {
                    $usuarioDto->$propertyName = $this->accessor->getValue($row[0], $propertyName);
                }
            }
            $qe = $this->em->createQuery('select e from ' . Empleado::class . ' e where e.usuario = ' .$row[0]->getId());
            if ($empleado = $qe->getOneOrNullResult()) {
                $usuarioDto->napidb = strtolower($empleado->getSsrsDb());
            }
            $this->api->migrate->usuario->migrate($usuarioDto);
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

    private function progresoMigrate()
    {
        /** @var Progreso $progreso */
        foreach ($this->em->getRepository(Progreso::class)->findAll() as $progreso) {
            $this->api->migrate->progreso->migrate($progreso, ['groups' => 'selr:migrate']);
            foreach ($progreso->getRespuestas() as $respuesta) {
                $this->api->migrate->progreso->migrateRespuesta($respuesta, ['groups' => 'selr:migrate']);
            }
            $this->progressBarAdvance();
        }
    }

    private function hvMigrate()
    {
        /** @var Hv $hv */
        foreach ($this->em->getRepository(Hv::class)->findAll() as $hv) {
            if ($hv->getUsuario()) {
                $context = ['groups' => 'selr:migrate'];
                $this->api->migrate->hv->migrate($hv, $context);
                $this->api->migrate->hv->estudios($hv->getIdentificacion(), $hv->getEstudios(), $context);
                $this->api->migrate->hv->experiencias($hv->getIdentificacion(), $hv->getExperiencias(), $context);
                if ($adjunto = $hv->getAdjunto()) {
                    $path = $this->empresa . "/hv-adjunto/".$adjunto->getFilename();
                    if (!$this->selrFilesystem->has($path)) {
                        $this->selrFilesystem->writeStream($path, $this->uploaderHelper->readStream($adjunto->getFilePath(), false));
                    }
                    $this->api->migrate->hv->adjunto($hv->getIdentificacion(), $adjunto, $context);
                }
            }
            $this->progressBarAdvance();
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