<?php

namespace App\Command\NovasoftImport;

use App\Command\Helpers\ConnectToLogEvent;
use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\RangoPeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Entity\Main\Empleado;
use App\Event\Event\LogEvent;
use App\Repository\Novasoft\Report\Nomina\NominaRepository;
use App\Service\Novasoft\Report\ReportFactory;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class NiNominaCommand extends NiCommand
{
    use PeriodoOption,
        RangoPeriodoOption,
        ConnectToLogEvent,
        SearchByConvenioOrEmpleado {
            getEmpleados as getEmpleadosTrait;
        }
    use ConsoleProgressBar;


    protected static $defaultName = 'sel:ni:nomina';

    /**
     * @var NominaRepository
     */
    private $reporteNominaRepository;


    private $empleadoSsrsDbs = [];
    /**
     * @var ReportFactory
     */
    private $reportFactory;
    /**
     * @var ReportCacheHandler
     */
    private $reportCacheHandler;

    /**
     * @var Empleado[]
     */
    private $empleados = null;


    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                ReportFactory $reportFactory, NominaRepository $reporteNominaRepository,
                                ReportCacheHandler $reportCacheHandler)
    {
        $this->optionInicioDescription = 'fecha desde Y-m-d. [omita y se toma desde 2017-02-01]';

        parent::__construct($annotationReader, $eventDispatcher);

        $this->reporteNominaRepository = $reporteNominaRepository;
        $this->reportFactory = $reportFactory;
        $this->reportCacheHandler = $reportCacheHandler;
    }


    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Actualizar reportes de nomina')
            ->addOption('dont-update', null, InputOption::VALUE_NONE,
                'Si certificado ya existe no actualiza');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $empleados = $this->getEmpleados();

        $desde = $this->getInicio($input, '2017-02-01');
        $hasta = $this->getFin($input, 'Y-m-t');

        $dontUpdate = $input->getOption('dont-update');


        foreach($empleados as $empleado) {
            $dataLog = sprintf("%s %d %d %s",
                $empleado->getConvenio()->getCodigo(),
                $empleado->getUsuario()->getId(),
                $empleado->getUsuario()->getIdentificacion(),
                $empleado->getUsuario()->getNombreCompleto(true));

            $this->periodo ?
                $this->info($this->periodo->format('Y-m') . " $dataLog") :
                $this->info($dataLog, [$desde ? $desde->format('Y-m-d') : null, $hasta->format('Y-m-d')]);

            $reporteNomina = $this->reportFactory->nomina(
                $empleado->getUsuario()->getIdentificacion(), $desde, $hasta, $empleado->getSsrsDb());

            $importer = $reporteNomina->getImporter()->setUpdate(!$dontUpdate);

            $importer->importMapAndPdf();
            $this->reportCacheHandler->saveCache($empleado->getUsuario(), 'novasoft', 'Nomina');

            $this->progressBarAdvance();
        }
    }

    protected function getLogEvent()
    {
        return LogEvent::class;
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->getEmpleados());
    }

    /**
     * @return Empleado|Empleado[]|null
     * @throws \Doctrine\ORM\Query\QueryException
     */
    protected function getEmpleados()
    {
        if($this->empleados === null) {
            $this->empleados = $this->getEmpleadosTrait();
        }
        return $this->empleados;
    }
}
