<?php


namespace App\Command\Selr;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Usuario;
use Doctrine\Common\Annotations\Reader;
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
        'usuario' => Usuario::class
    ];

    private $entities;
    /**
     * @var Api
     */
    private $api;

    public function __construct(
        Reader $annotationReader,
        EventDispatcherInterface $dispatcher,
        Api $api
    ) {
        parent::__construct($annotationReader, $dispatcher);
        $this->api = $api;
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
            $q = $this->em->createQuery('select x from '.$entityClass.'  x');
            $iterableResult = $q->iterate();
            foreach ($iterableResult as $row) {
                $this->api->migrate->usuario->migrate($row[0]);
                // do stuff with the data in the row
                // detach from Doctrine, so that it can be Garbage-Collected immediately
                $this->em->detach($row[0]);
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