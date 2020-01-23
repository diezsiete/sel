<?php

namespace App\Service\ServicioEmpleados\Report;


use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\Nomina;
use App\Entity\ServicioEmpleados\ReportCache;
use App\Repository\ServicioEmpleados\ReportCacheRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\Report\CertificadoIngresosReport as NovasoftCertificadoIngresos;
use App\Service\Novasoft\Report\Report\NominaReport;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Determina que hacer al popular los registros de tabla de
 * Class CacheHandler
 * @package App\Service\ServicioEmpleados\Report
 */
class ReportCacheHandler
{
    /**
     * @var ReportCacheRepository
     */
    private $reportCacheRepo;
    /**
     * @var HalconReportFactory
     */
    private $halconReportFactory;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var NovasoftReportFactory
     */
    private $novasoftReportFactory;
    /**
     * @var NovasoftEmpleadoService
     */
    private $novasoftEmpleadoService;
    /**
     * @var Configuracion
     */
    private $config;

    public function __construct(ReportCacheRepository $reportCacheRepo, EntityManagerInterface $em, Configuracion $config,
                                NovasoftEmpleadoService $novasoftEmpleadoService,
                                HalconReportFactory $halconReportFactory, NovasoftReportFactory $novasoftReportFactory)
    {
        $this->reportCacheRepo = $reportCacheRepo;
        $this->halconReportFactory = $halconReportFactory;
        $this->novasoftReportFactory = $novasoftReportFactory;
        $this->em = $em;
        $this->novasoftEmpleadoService = $novasoftEmpleadoService;
        $this->config = $config;
    }

    /**
     * @param Usuario|ReportCache $usuarioOrCache
     * @param $source
     * @param $reportEntityClass
     * @throws Exception
     */
    public function saveCache($usuarioOrCache, $source = null, $reportEntityClass = null)
    {
        $prevCache = $usuarioOrCache instanceof ReportCache
            ? $usuarioOrCache
            : $this->reportCacheRepo->findLastCacheForReport($usuarioOrCache, $source, $reportEntityClass);

        if($prevCache) {
            $prevCache->setLastUpdate(new DateTime());
        } else {
            $cache = (new ReportCache())
                ->setUsuario($usuarioOrCache)
                ->setSource($source)
                ->setReport($reportEntityClass)
                ->setLastUpdate(new DateTime());
            $this->em->persist($cache);
        }
        $this->em->flush();
    }

    public function handle(Usuario $usuario, $reportEntityClass)
    {
        $reportEntityClassClean = $this->getReportEntityClassClean($reportEntityClass);
        if($usuario->esRol('ROLE_HALCON')) {
            if (!$cache = $this->reportCacheRepo->findLastCacheForReport($usuario, 'halcon', $reportEntityClassClean)) {
                $report = $this->halconReportFactory->getReport($reportEntityClassClean);
                $report
                    ->setUsuario($usuario)
                    ->getImporter()
                    ->importMap();
                $this->saveCache($usuario, 'halcon', $reportEntityClassClean);
            }
        }

        if($usuario->esRol('ROLE_EMPLEADO')) {
            $report = $this->novasoftReportFactory->getReport($reportEntityClassClean);
            switch($reportEntityClassClean) {
                case 'Nomina':
                    $this->handleNovasoftNomina($report, $usuario);
                    break;
                case 'CertificadoIngresos':
                    /** @noinspection PhpParamsInspection */
                    $this->handleNovasftCertificadoIngresos($report, $usuario);
                    break;
                default:
                    if (!$cache = $this->reportCacheRepo->findLastCacheForReport($usuario, 'novasoft', $reportEntityClassClean)) {
                        $report
                            ->setUsuario($usuario)
                            ->getImporter()
                            ->importMapAndPdf();
                        $this->saveCache($usuario, 'novasoft', $reportEntityClassClean);
                    }
            }
        }
    }

    public function handleAll(Usuario $usuario)
    {
        foreach($this->config->servicioEmpleados()->getReportsNames() as $reportName) {
            $this->handle($usuario, $reportName);
        }
    }

    public function hasCacheToRenew(Usuario $usuario, $source = null)
    {
        $hasCacheToRenew = false;
        $sourcesRoles = ['halcon' => 'ROLE_HALCON', 'novasoft' => 'ROLE_EMPLEADO'];
        if(!$source) {
            foreach(array_keys($sourcesRoles) as $source) {
                $hasCacheToRenew = $hasCacheToRenew ? $hasCacheToRenew : $this->hasCacheToRenew($usuario, $source);
            }
        } else {
            if($usuario->esRol($sourcesRoles[$source])) {
                $reportsNames = $this->config->servicioEmpleados()->getReportsNames();
                $caches = $this->reportCacheRepo->findLastCacheForReport($usuario, $source);

                $hasCacheToRenew = array_reduce($reportsNames, function ($hasCacheToRenew, $reportName) use ($caches) {
                    return $hasCacheToRenew
                        ? true
                        : !((bool)$caches->matching(ReportCacheRepository::filterByReportCriteria($reportName))->count());
                }, false);

                if(!$hasCacheToRenew && $source === 'novasoft') {
                    // TODO configurar fecha
                }
            }
        }
        return $hasCacheToRenew;
    }

    private function handleNovasoftNomina(NominaReport $report, Usuario $usuario)
    {
        $cache = $this->reportCacheRepo->findLastCacheForReport($usuario, 'novasoft', 'Nomina');
        if (!$cache) {
            $report
                ->setUsuario($usuario)
                ->setDb($this->novasoftEmpleadoService->getSsrsDb($usuario->getIdentificacion()))
                ->getImporter()
                ->importMapAndPdf();
            $this->saveCache($usuario, 'novasoft', 'Nomina');
        } else {
            /*$date = new DateTime();
            $fechaInicio = DateTime::createFromFormat('Y-m-d', $date->format('Y-m-') . '01');
            $fechaFin = DateTime::createFromFormat('Y-m-d', $date->format('Y-m-t'));
            $report->setUsuario($usuario)
                ->setParameterFechaInicio($fechaInicio)
                ->setParameterFechaFin($fechaFin)
                ->setDb($this->novasoftEmpleadoService->getSsrsDb($usuario->getIdentificacion()));

            $report->getImporter()->importMapAndPdf();

            $this->saveCache($cache);*/
        }
    }

    private function handleNovasftCertificadoIngresos(NovasoftCertificadoIngresos $ciReport, Usuario $usuario)
    {
        //TODO pasar estos años a parametros de configuracion
        $anos = [2017, 2018];
        $ciReport->setUsuario($usuario);
        foreach($anos as $ano) {
            $ciReport
                ->setParameterAno($ano)
                ->getImporter()
                ->importMapAndPdf();
        }
    }

    private function getReportEntityClassClean($reporEntityClass)
    {
        $reporEntityClassClean = $reporEntityClass;
        if(preg_match('/.*\\\\(\w+)$/', $reporEntityClass, $matches)) {
            $reporEntityClassClean = $matches[1];
        }
        return $reporEntityClassClean;
    }

    private function forEachReportHasCaches(Usuario $usuario, string $source)
    {
        $reportsNames = $this->config->servicioEmpleados()->getReportsNames();
        $caches = $this->reportCacheRepo->findLastCacheForReport($usuario, $source);

        return array_reduce($reportsNames, function ($hasCacheToRenew, $reportName) use ($caches) {
            return $hasCacheToRenew
                ? true
                : !((bool)$caches->matching(ReportCacheRepository::filterByReportCriteria($reportName))->count());
        }, false);
    }
}