<?php

namespace App\Command\ServicioEmpleados\ReportCache;


use App\Command\Helpers\BatchProcessing;
use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Command\Helpers\ServicioEmpleados\ReportCache as ReportCacheTrait;
use App\Entity\Main\Usuario;
use App\Entity\Novasoft\Report\CertificadoIngresos;
use App\Entity\Novasoft\Report\CertificadoLaboral;
use App\Entity\Novasoft\Report\LiquidacionContrato;
use App\Entity\Novasoft\Report\Nomina\Nomina;
use App\Entity\ServicioEmpleados\CertificadoIngresos as SeCertificadoIngresos;
use App\Entity\ServicioEmpleados\CertificadoLaboral as SeCertificadoLaboral;
use App\Entity\ServicioEmpleados\LiquidacionContrato as SeLiquidacionContrato;
use App\Entity\ServicioEmpleados\Nomina as SeNomina;
use App\Entity\ServicioEmpleados\ReportCache;
use App\Repository\Novasoft\SqlServer\NominaRepository as SqlServerNominaRepository;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use SSRS\SSRSReportException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
// Necesario por bug de ReportCacheTrait utiliza trait ConnectToLogEvent que necesita esta y no es importada automaticamente
use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImportInitialCommand extends TraitableCommand
{
    use SelCommandTrait,
        ReportCacheTrait,
        BatchProcessing,
        ConsoleProgressBar;

    /**
     * @var HalconReportFactory
     */
    private $halconReportFactory;
    /**
     * @var NovasoftReportFactory
     */
    private $novasoftReportFactory;
    /**
     * @var NovasoftEmpleadoService
     */
    private $novasoftEmpleadoService;

    /**
     * @var SqlServerNominaRepository
     */
    private $nominaRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                HalconReportFactory $halconReportFactory,
                                NovasoftReportFactory $novasoftReportFactory,
                                NovasoftEmpleadoService $novasoftEmpleadoService, SqlServerNominaRepository $nominaRepository)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->halconReportFactory = $halconReportFactory;
        $this->novasoftReportFactory = $novasoftReportFactory;
        $this->novasoftEmpleadoService = $novasoftEmpleadoService;
        $this->nominaRepository = $nominaRepository;
    }

    protected static $defaultName = "sel:se:report-cache:import-initial";

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $this->getSource($input);
        $reportCacheRepo = $this->em->getRepository(ReportCache::class);

        foreach($this->batch($this->getUsuariosIdents($input)) as $identificacion) {
            if($identificacion) {
                $usuario = $this->getBatchUsuario($identificacion);
                foreach ($this->getReports($input) as $report) {
                    if (!$cache = $reportCacheRepo->findLastCacheForReport($usuario, $source, $report)) {
                        if ($source === 'novasoft') {
                            $this->handleNoCacheNovasoft($report, $usuario);
                        } else {
                            $this->handleNoCacheHalcon($report, $usuario);
                        }
                        //$this->em->persist($this->reportCacheHandler->buildNewCache($usuario, $source, $report));
                    }
                }
            }
        }
    }

    private function handleNoCacheNovasoft(string $report, Usuario $usuario)
    {
        $fechFin = DateTime::createFromFormat('Y-m-d', '2017-03-31');
        $this->nominaRepository->findByIdentAndPeriodo($usuario->getIdentificacion(), null, $fechFin);

        $report = $this->novasoftReportFactory->nomina($usuario->getIdentificacion(), null, $fechFin);

        $assoc = $report->renderAssociative();
        $noms = [];
        foreach($assoc as $row) {
            $noms[] = [
                'textbox22' => $row['textbox22'],
                'salario' => $row['salario']
            ];
        }
        dump($noms);
    }

    private function handleNoCacheHalcon($report, $usuario)
    {
        $reportHalcon = $this->halconReportFactory->getReport($report)
            ->setUsuario($usuario);
        foreach ($reportHalcon->renderMap() as $halconEntity) {
            $seEntity = $reportHalcon->getImporter()->buildSeEntity($halconEntity);
            if ($seEntity) {
                $this->em->persist($seEntity);
            }
        }
    }



    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return $this->getUsuariosIdents($input, true);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param InputInterface $input
     * @param bool $count
     * @return string[]|int
     */
    protected function getUsuariosIdents(InputInterface $input, $count = false)
    {
        $source = $input->getOption('source');
        $usuario = $input->getArgument('usuario');

        $rol = $this->configuracion->servicioEmpleados()->getRolBySource($source);

        $usuarioRepository = $this->em->getRepository(Usuario::class);

        return $count
            ? $usuarioRepository->countIdsByRol($rol, $usuario)
            : $usuarioRepository->findIdentsByRol($rol, $usuario);


    }


    protected function seNomina(Nomina $entity)
    {
        return (new SeNomina())
            ->setFecha($entity->getFecha())
            ->setConvenio($entity->getConvenioCodigoNombre())
            ->setSourceNovasoft()
            ->setSourceId($entity->getId())
            ->setUsuario($entity->getUsuario());
    }

    protected function seCertificadoLaboral(CertificadoLaboral $certificadoLaboral)
    {
        return (new SeCertificadoLaboral())
            ->setFechaIngreso($certificadoLaboral->getFechaIngreso())
            ->setFechaRetiro($certificadoLaboral->getFechaEgreso())
            ->setConvenio($certificadoLaboral->getEmpresaUsuaria())
            ->setSourceNovasoft()
            ->setSourceId($certificadoLaboral->getId())
            ->setUsuario($certificadoLaboral->getUsuario());
    }

    protected function seCertificadoIngresos(CertificadoIngresos $certificado)
    {
        $periodo = DateTime::createFromFormat("Y-m-d", $certificado->getPeriodoCertificacionDe()->format('Y') . '-01-01');
        return (new SeCertificadoIngresos())->setPeriodo($periodo)
            ->setSourceNovasoft()
            ->setSourceId($certificado->getId())
            ->setUsuario($certificado->getUsuario());
    }

    protected function seLiquidacionContrato(LiquidacionContrato $liquidacionContrato)
    {
        return (new SeLiquidacionContrato())
            ->setFechaIngreso($liquidacionContrato->getFechaIngreso())
            ->setFechaRetiro($liquidacionContrato->getFechaRetiro())
            ->setContrato($liquidacionContrato->getNumeroContrato())
            ->setSourceNovasoft()
            ->setSourceId($liquidacionContrato->getId())
            ->setUsuario($liquidacionContrato->getUsuario());
    }

    protected function findUsuarioQuery(InputInterface $input, $count = false)
    {
        // no se usa
    }


}