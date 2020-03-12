<?php


namespace App\Command\NovasoftApi\Import;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TestOption;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Service\Novasoft\Api\Client\ConvenioClient;
use App\Service\Novasoft\Api\Importer\EmpleadoImporter;
use App\Service\Novasoft\Api\Client\NovasoftApiClient;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImportEmpleadoCommand extends TraitableCommand
{
    use Loggable,
        TestOption,
        ConsoleProgressBar;

    protected static $defaultName = 'sel:napi:import:empleado';

    /**
     * @var EmpleadoImporter
     */
    private $importer;
    /**
     * @var ConvenioClient
     */
    private $convenioClient;

    private $empleadosCollection = [];

    private $conveniosCollection;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                EmpleadoImporter $importer, ConvenioClient $convenioClient)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->importer = $importer;
        $this->convenioClient = $convenioClient;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('search', InputArgument::OPTIONAL,
            'identificacion o convenio');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $search = $input->getArgument('search');
        if(is_numeric($search)) {
            $this->importEmpleado($search);
        } elseif($search) {
            $this->importEmpleados($search);
        } else {
            $conveniosRaw = $this->convenioClient->getConveniosRaw();
            foreach($conveniosRaw as $convenioRaw) {
                $this->importEmpleados($convenioRaw['codigo']);
            }
        }
    }

    protected function importEmpleados($codigoConvenio)
    {
        $collection = $this->getEmpleadosCollection($codigoConvenio);
        foreach($collection as $empleado) {
            $this->importEmpleado($empleado);
        }
    }

    protected function importEmpleado($search)
    {
        $empleado = is_object($search) ? $search : $this->importer->getNapiEmpleado($search);
        if($empleado) {
            $empleadoMessage = $empleado->getId() ? '[empleado update]' : '[empleado insert]';
            $usuarioMessage = $empleado->getUsuario()->getId() ? '[usuario update]' : '[usuario insert]';

            if(!$this->isTest()) {
                $this->importer->importEmpleado($empleado);
            }

            $this->info(sprintf('%-12s %-16s %-17s %s %s', $empleado->getConvenio()->getCodigo(), $usuarioMessage, $empleadoMessage,
                $empleado->getUsuario()->getNombreCompleto(), $empleado->getUsuario()->getIdentificacion()));
        } else {
            $identificacion = $empleado ? $empleado->getUsuario()->getIdentificacion() : $search;
            $this->error(sprintf('error importando empleado "%s"', $identificacion));
        }
        $this->progressBarAdvance();
    }


    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        $search = $input->getArgument('search');
        if (is_numeric($search)) {
            return 1;
        }
        if($search) {
            return count($this->getEmpleadosCollection($search));
        }
        return count($this->importer->getClient()->getEmpleadosByConvenio());
    }

    protected function getEmpleadosCollection($codigoConvenio)
    {
        if(!isset($this->empleadosCollection[$codigoConvenio])) {
            $this->empleadosCollection[$codigoConvenio] = $this->importer->getClient()->getEmpleadosByConvenio($codigoConvenio);
        }
        return $this->empleadosCollection[$codigoConvenio];
    }

    protected function getConveniosCollection()
    {
        if(!$this->conveniosCollection) {
            $this->conveniosCollection = $this->convenioClient->getConveniosRaw();
        }
        return $this->conveniosCollection;
    }
}