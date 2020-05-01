<?php


namespace App\Command\NovasoftApi\Import;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\RangoPeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TestOption;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use App\Service\Napi\EmpleadoService;
use App\Service\Novasoft\Api\Client\ConvenioClient;
use App\Service\Napi\Client\NapiClient;
use App\Service\Novasoft\Api\Importer\EmpleadoImporter;
use App\Service\Novasoft\Api\Client\NovasoftApiClient;
use DateTimeInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImportEmpleadoCommand extends TraitableCommand
{
    use Loggable,
        TestOption,
        PeriodoOption,
        RangoPeriodoOption,
        ConsoleProgressBar,
        SelCommandTrait;

    protected static $defaultName = 'sel:napi:import:empleado';

    /**
     * @var NapiClient
     */
    private $napiClient;
    /**
     * @var EmpleadoService
     */
    private $empleadoService;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                NapiClient $napiClient, EmpleadoService $empleadoService)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->napiClient = $napiClient;
        $this->empleadoService = $empleadoService;
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
            /** @var Empleado|null $empleado */
            if($empleado = $this->napiClient->itemOperations(Empleado::class)->get($search)) {
                $this->importEmpleado($empleado);
            }
        } elseif($search) {
            $this->importEmpleados($search);
        } else {
            foreach($this->em->getRepository(Convenio::class)->findAll() as $convenio) {
                $this->importEmpleados($convenio->getCodigo());
            }
        }
    }

    protected function importEmpleados($codigoConvenio)
    {
        $collection = $this->getEmpleadosCollection($codigoConvenio);
        foreach($collection as $empleado) {
            $this->importEmpleado($empleado);
        }
        $this->em->clear();
    }

    protected function importEmpleado(Empleado $empleado)
    {
        $empleadoMessage = $empleado->getId() ? '[empleado update]' : '[empleado insert]';
        $usuarioMessage = $empleado->getUsuario()->getId() ? '[usuario update]' : '[usuario insert]';

        if(!$this->isTest()) {
            $this->empleadoService->importEmpleado($empleado);
        }

        $this->info(sprintf('%-12s %-16s %-17s %s %s', $empleado->getConvenio()->getCodigo(), $usuarioMessage, $empleadoMessage,
            $empleado->getUsuario()->getNombreCompleto(), $empleado->getUsuario()->getIdentificacion()));

        $this->progressBarAdvance();
    }


    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        $search = $this->input->getArgument('search');
        if(is_numeric($search)) {
            return 1;
        }
        return count($this->getEmpleadosCollection($search));
    }

    protected function getEmpleadosCollection($codigo = null)
    {
        $fechaIngreso = $this->getInicio();
        $fechaRetiro = $this->getFin();

        $operationParameters = [];
        if($fechaIngreso) {
            $operationParameters['fechaIngreso'] = $fechaIngreso->format('Y-m-d');
        }
        if($fechaRetiro) {
            $operationParameters['fechaRetiro'] = $fechaRetiro->format('Y-m-d');
        }
        if($codigo) {
            $operationParameters[is_numeric($codigo) ? 'identificacion' : 'codigo'] = $codigo;
        }
        return $this->napiClient->collectionOperations(Empleado::class)->get($operationParameters);
    }
}