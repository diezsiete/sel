<?php


namespace App\Command\NovasoftApi\Import;


use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Service\Novasoft\Api\Importer\EmpleadoImporter;
use App\Service\Novasoft\Api\Client\NovasoftApiClient;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImportEmpleadoCommand extends TraitableCommand
{
    protected static $defaultName = 'sel:napi:import:empleado';

    /**
     * @var \App\Service\Novasoft\Api\Client\NovasoftApiClient
     */
    private $client;
    /**
     * @var EmpleadoImporter
     */
    private $importer;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                EmpleadoImporter $importer)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->importer = $importer;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('search', InputArgument::REQUIRED,
            'identificacion');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $search = $input->getArgument('search');
        $this->importer->import($search);
    }


}