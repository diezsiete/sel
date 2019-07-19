<?php


namespace App\Command\Autoliquidacion;


use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AutoliquidacionGenerarCommand extends Command
{
    protected static $defaultName = 'autoliquidacion:generar';
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;

    public function __construct(ConvenioRepository $convenioRepository, EmpleadoRepository $empleadoRepository)
    {
        parent::__construct();
        $this->convenioRepository = $convenioRepository;
        $this->empleadoRepository = $empleadoRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('convenio', InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'codigos convenios para crear autoliqs y descargar. Omita y se toman todos' )
            ->addOption('periodo', 'p', InputOption::VALUE_REQUIRED,
                'Especifique mes en formato Y-m')
            ->addOption('ssrs_db', null,InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY,
                'Novasoft database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $input->getOption('periodo');
        $periodo = \DateTime::createFromFormat('Y-m-d', "$periodo-01");

        $empleados = [];
        if($convenios  = $input->getArgument('convenios')) {
            $empleados = $this->empleadoRepository->findByConvenio($convenios);
        } elseif($ssrsDb = $input->getOption('ssrs_db')) {
            $empleados = $this->empleadoRepository->findBySsrsDb($ssrsDb);
        } else {

        }
    }

    private function filterEmpleadosInRangoPeriodo($empleados, $periodo)
    {
        $periodoFin = \DateTime::createFromFormat('Y-m-d', $periodo->format('Y-m-t'));

        $empleadosFiltered = [];

        foreach ($empleados as $empleado) {
            $fechaIngreso = $empleado->getFechaIngreso();
            $fechaRetiro  = $empleado->getFechaRetiro();
            if ($fechaIngreso < $periodoFin && (!$fechaRetiro || $fechaRetiro > $periodo)) {
                $empleadosFiltered[] = $empleado;
            }
        }
        return $empleadosFiltered;
    }


}