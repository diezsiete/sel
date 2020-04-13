<?php

namespace App\Service\ServicioEmpleados\Report;


use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ReportCache;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Event\Event\LogEvent;
use App\Helper\Loggable;
use App\Repository\ServicioEmpleados\ReportCacheRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\Report\CertificadoIngresosReport as NovasoftCertificadoIngresos;
use App\Service\Novasoft\Report\Report\LiquidacionContratoReport;
use App\Service\Novasoft\Report\Report\NominaReport;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use App\Service\Napi\Report\ReportFactory as NapiReportFactory;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use SSRS\SSRSReportException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Determina que hacer al popular los registros de tabla de
 * Class CacheHandler
 * @package App\Service\ServicioEmpleados\Report
 */
class ReportCacheHandler
{
    use Loggable;

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
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var ReportFactory
     */
    private $reportFactory;
    /**
     * @var NapiReportFactory
     */
    private $napiReportFactory;

    public function __construct(ReportCacheRepository $reportCacheRepo, EntityManagerInterface $em, Configuracion $config,
                                NovasoftEmpleadoService $novasoftEmpleadoService, EventDispatcherInterface $dispatcher,
                                HalconReportFactory $halconReportFactory, NovasoftReportFactory $novasoftReportFactory,
                                NapiReportFactory $napiReportFactory,
                                ReportFactory $reportFactory)
    {
        $this->reportCacheRepo = $reportCacheRepo;
        $this->halconReportFactory = $halconReportFactory;
        $this->novasoftReportFactory = $novasoftReportFactory;
        $this->em = $em;
        $this->novasoftEmpleadoService = $novasoftEmpleadoService;
        $this->config = $config;
        $this->dispatcher = $dispatcher;
        $this->reportFactory = $reportFactory;
        $this->napiReportFactory = $napiReportFactory;
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
            $this->em->persist($this->buildNewCache($usuarioOrCache, $source, $reportEntityClass));
        }
        $this->em->flush();
    }

    public function handleAll(Usuario $usuario)
    {
        foreach($this->config->servicioEmpleados()->getReportsNames() as $reportName) {
            try {
                $this->handle($usuario, $reportName);
            } catch (Exception $e) {
                // TODO
            }
        }
    }

    public function handle(Usuario $usuario, $reportEntityClass, $source = null)
    {
        if(!$source) {
            /** @noinspection SuspiciousLoopInspection */
            foreach($this->config->servicioEmpleados()->getSources() as $source) {
                $this->handle($usuario, $reportEntityClass, $source);
            }
        } else {
            switch($source) {
                case 'halcon':
                    $this->handleHalcon($usuario, $reportEntityClass);
                    break;
                case 'novasoft':
                    $this->handleNovasoft($usuario, $reportEntityClass);
                    break;
                case 'napi':
                    $this->handleNapi($usuario, $reportEntityClass);
                    break;
            }
        }
    }


    public function handleHalcon(Usuario $usuario, $reportEntityClass)
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
    }

    public function handleNapi(Usuario $usuario, $reportEntityClass)
    {
        $reportEntityClass = $this->getReportEntityClassClean($reportEntityClass);
        if($usuario->esRol('ROLE_EMPLEADO')) {
            if (!$cache = $this->hasCache($usuario, 'napi', $reportEntityClass)) {
                $report = $this->napiReportFactory->getReport($reportEntityClass);
                $report->import($usuario);
                //$this->saveCache($usuario, 'napi', $reportEntityClass);
            }
        }
    }

    /**
     * @param Usuario $usuario
     * @param $reportEntityClass
     * @param bool $ignoreRefreshInterval
     * @throws SSRSReportException
     */
    public function handleNovasoft(Usuario $usuario, $reportEntityClass, $ignoreRefreshInterval = false)
    {
        $reportEntityClassClean = $this->getReportEntityClassClean($reportEntityClass);
        if($usuario->esRol('ROLE_EMPLEADO')) {
            $report = $this->novasoftReportFactory->getReport($reportEntityClassClean)
                ->setUsuario($usuario)
                ->setDb($this->novasoftEmpleadoService->getSsrsDb($usuario->getIdentificacion()));

            $import = false;

            $cache = $this->reportCacheRepo->findLastCacheForReport($usuario, 'novasoft', $reportEntityClassClean);
            if (!$cache) {
                $import = true;
                switch($reportEntityClassClean) {
                    case 'Nomina':
                    case 'LiquidacionContrato':
                        $this->novasoftBuildNoCache($report);
                        break;
                    case 'CertificadoIngresos':
                        $this->novasoftBuildNoCacheCertificadoIngresos($report);
                        break;
                }
            } else {
                if ($ignoreRefreshInterval || $this->isRefreshIntervalOver($reportEntityClassClean, $cache->getLastUpdate())) {
                    $import = true;
                    switch ($reportEntityClassClean) {
                        case 'Nomina':
                        case 'LiquidacionContrato':
                            $this->novasoftBuildRefreshIntervalIsOver($report, $cache);
                            break;
                        case 'CertificadoIngresos':
                            $this->novasoftBuildRefreshIntervalIsOverCertficadoIngresos($report, $cache);
                            break;
                    }
                }
            }
            if($import) {
                $report
                    ->getImporter()
                    ->importMapAndPdf();
                $this->saveCache($cache ? $cache : $usuario, 'novasoft', $reportEntityClassClean);
            }
        }
    }

    /**
     * @param Usuario $usuario
     * @param $reportEntityName
     * @param null $source
     */
    public function delete(Usuario $usuario, $reportEntityName, $source = null)
    {
        if(!$source) {
            foreach($this->config->servicioEmpleados()->getSources() as $source) {
                $this->delete($usuario, $reportEntityName, $source);
            }
        } else {
            $seEntityName = $this->config->servicioEmpleados()->getReportEntityClass($reportEntityName);

            $this->deleteReportEntities($seEntityName, ['usuario' => $usuario, 'source' => $source],
                function(ServicioEmpleadosReport $entity) use ($reportEntityName) {
                    $pdfDeleted = $this->reportFactory->getReportByEntity($entity)->getImporter()->deletePdf();
                    $this->info($pdfDeleted ? "file '$pdfDeleted' deleted" : "no file found to delete");
                }
            );

            if($source === 'novasoft') {
                $novasoftEntityName = $this->config->servicioEmpleados()->getReportEntityClass($reportEntityName, $source);
                $this->deleteReportEntities($novasoftEntityName, ['usuario' => $usuario]);
            }

            $reportCache = $this->em->getRepository(ReportCache::class)
                ->findLastCacheForReport($usuario, $source, $reportEntityName);
            if($reportCache) {
                $this->em->remove($reportCache);
                $this->info("report_cache deleted for '$reportEntityName'");
            }
            $this->em->flush();
        }
    }

    public function hasCacheToRenew(Usuario $usuario, $source = null, $reports = [])
    {
        $reports = is_array($reports) ? $reports : [$reports];
        $hasCacheToRenew = false;
        if(!$source) {
            foreach($this->config->servicioEmpleados()->getSources() as $source) {
                $hasCacheToRenew = $hasCacheToRenew ? $hasCacheToRenew : $this->hasCacheToRenew($usuario, $source, $reports);
            }
        } else {
            if($this->config->servicioEmpleados()->usuarioHasRoleForSource($usuario, $source)) {
                $reportsNames = $reports ? $reports : $this->config->servicioEmpleados()->getReportsNames();
                $caches = $this->reportCacheRepo->findLastCacheForReport($usuario, $source);

                $hasCacheToRenew = array_reduce($reportsNames, function ($hasCacheToRenew, $reportName) use ($caches) {
                    return $hasCacheToRenew
                        ? true
                        : !((bool)$caches->matching(ReportCacheRepository::filterByReportCriteria($reportName))->count());
                }, false);

                if(!$hasCacheToRenew && $source === 'novasoft') {
                    foreach($reportsNames as $reportName) {
                        if(!$hasCacheToRenew) {
                            /** @var ReportCache $cache */
                            $cache = $caches->matching(ReportCacheRepository::filterByReportCriteria($reportName))->first();
                            $hasCacheToRenew = $this->isRefreshIntervalOver($reportName, $cache->getLastUpdate());
                        }
                    }
                }
            }
        }
        return $hasCacheToRenew;
    }



    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param string $reportEntityName
     * @param DateTime $lastUpdate
     * @return bool
     */
    public function isRefreshIntervalOver($reportEntityName, $lastUpdate)
    {
        $reportConfig = $this->config->servicioEmpleados()->getReportConfig($reportEntityName);
        $lastUpdatePlusInterval = (clone $lastUpdate)->add($reportConfig->getRefreshInterval());
        $now = new DateTime();

        $isOver = $now > $lastUpdatePlusInterval;

        $this->info(sprintf('%s (lastUpdate(%s) + refreshInterval(%s) = %s) < now(%s) ? %d',
            $reportEntityName,
            $lastUpdate->format('Y-m-d H:i:s'),
            $reportConfig->getRefreshIntervalSpec(),
            $lastUpdatePlusInterval->format('Y-m-d H:i:s'),
            $now->format('Y-m-d H:i:s'),
            $isOver
        ));

        return $isOver;
    }

    public function buildNewCache(Usuario $usuario, $source, $reportEntityClass)
    {
        return (new ReportCache())
            ->setUsuario($usuario)
            ->setSource($source)
            ->setReport($reportEntityClass)
            ->setLastUpdate(new DateTime());
    }

    /**
     * @param NominaReport|LiquidacionContratoReport $report
     * @throws Exception
     */
    private function novasoftBuildNoCache($report)
    {
        $fechaFin = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m-t'));
        $report
            ->setParameterFechaInicio()
            ->setParameterFechaFin($fechaFin);
    }

    /**
     * @param NominaReport|LiquidacionContratoReport $report
     * @param ReportCache $cache
     * @throws Exception
     */
    private function novasoftBuildRefreshIntervalIsOver($report, ReportCache $cache)
    {
        $lastUpdate = $cache->getLastUpdate();
        $date = new DateTime();
        $fechaInicio = DateTime::createFromFormat('Y-m-d', $lastUpdate->format('Y-m-') . '01');
        $fechaFin = DateTime::createFromFormat('Y-m-d', $date->format('Y-m-t'));
        $report
            ->setParameterFechaInicio($fechaInicio)
            ->setParameterFechaFin($fechaFin);
    }


    private function novasoftBuildNoCacheCertificadoIngresos(NovasoftCertificadoIngresos $ciReport)
    {
        $anos = $this->config->servicioEmpleados()->getCertificadoIngresosConfig()->getAnos();
        $ciReport->setParameterAno($anos);
    }

    private function novasoftBuildRefreshIntervalIsOverCertficadoIngresos(NovasoftCertificadoIngresos $ciReport, ReportCache $cache)
    {
        $anos = $this->config->servicioEmpleados()->getCertificadoIngresosConfig()->getAnos();
        // actualizamos los dos ultimos años
        $anosUpdate = [];
        while(count($anosUpdate) < 2) {
            $anosUpdate[] = array_shift($anos);
        }
        $ciReport->setParameterAno($anosUpdate);
    }


    /**
     * @param $reporEntityClass
     * @return mixed
     * @deprecated TODO instead use Utils Symbol
     */
    private function getReportEntityClassClean($reporEntityClass)
    {
        $reporEntityClassClean = $reporEntityClass;
        if(preg_match('/.*\\\\(\w+)$/', $reporEntityClass, $matches)) {
            $reporEntityClassClean = $matches[1];
        }
        return $reporEntityClassClean;
    }

    private function deleteReportEntities($entityName, $criteria, $callbackEachEntity = '')
    {
        $entities = $this->em->getRepository($entityName)->findBy($criteria);
        foreach($entities as $entity) {
            $this->em->remove($entity);
            $this->info(sprintf('%s deleted', $entityName));
            if($callbackEachEntity) {
                $callbackEachEntity($entity);
            }
        }
    }

    private function hasCache(Usuario $usuario, $source, $reportEntityClass): bool
    {
        return  (bool) $this->reportCacheRepo->findLastCacheForReport($usuario, $source, $reportEntityClass);
    }

    public function log($level, $message, array $context = array())
    {
        $this->dispatcher->dispatch(LogEvent::$level($message, $context));
    }
}