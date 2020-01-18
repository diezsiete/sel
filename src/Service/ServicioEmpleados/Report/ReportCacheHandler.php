<?php

namespace App\Service\ServicioEmpleados\Report;


use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\ReportCache;
use App\Repository\ServicioEmpleados\ReportCacheRepository;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use App\Service\Novasoft\Report\Report\CertificadoIngresosReport as NovasoftCertificadoIngresos;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(ReportCacheRepository $reportCacheRepo, EntityManagerInterface $em,
                                HalconReportFactory $halconReportFactory, NovasoftReportFactory $novasoftReportFactory)
    {
        $this->reportCacheRepo = $reportCacheRepo;
        $this->halconReportFactory = $halconReportFactory;
        $this->novasoftReportFactory = $novasoftReportFactory;
        $this->em = $em;
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
            switch($reportEntityClass) {
                case CertificadoIngresos::class:
                    /** @noinspection PhpParamsInspection */
                    $this->handleNovasftCertificadoIngresos($report, $usuario);
                    break;
                default:
                    if (!$cache = $this->reportCacheRepo->findLastCacheForReport($usuario, 'novasoft', $reportEntityClass)) {
                        $report
                            ->setUsuario($usuario)
                            ->getImporter()
                            ->importMap();
                        // $this->saveCache($usuario, 'novasoft', $reportEntityClass);
                    }
            }
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
                ->importMap();
        }
    }



    private function saveCache(Usuario $usuario, $source, $reportEntityClass)
    {
        if($prevCache = $this->reportCacheRepo->findLastCacheForReport($usuario, $source, $reportEntityClass)) {
            $prevCache->setLastUpdate(new DateTime());
        } else {
            $cache = (new ReportCache())
                ->setUsuario($usuario)
                ->setSource($source)
                ->setReport($reportEntityClass)
                ->setLastUpdate(new DateTime());
            $this->em->persist($cache);
        }
        $this->em->flush();
    }

    private function getReportEntityClassClean($reporEntityClass)
    {
        $reporEntityClassClean = $reporEntityClass;
        if(preg_match('/.*\\\\(\w+)$/', $reporEntityClass, $matches)) {
            $reporEntityClassClean = $matches[1];
        }
        return $reporEntityClassClean;
    }

}