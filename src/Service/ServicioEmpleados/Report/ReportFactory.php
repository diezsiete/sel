<?php

namespace App\Service\ServicioEmpleados\Report;

use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\CertificadoLaboral;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\Nomina;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Service\Halcon\Report\Report\CertificadoLaboralReport as HalconCertificadoLaboralReport;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport as HalconLiquidacionContratoReport;
use App\Service\Napi\Report\ReportFactory as NapiReportFactory;
use App\Service\Napi\Report\Report\CertificadoIngresosReport as NapiCertificadoIngresosReport;
use App\Entity\Napi\Report\ServicioEmpleados\Nomina as NapiNomina;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoLaboral as NapiCertificadoLaboral;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoIngresos as NapiCertificadoIngresos;
use App\Entity\Napi\Report\ServicioEmpleados\LiquidacionContrato as NapiLiquidacionContrato;
use App\Service\Napi\Report\SsrsReport;
use App\Service\Novasoft\Report\Report\LiquidacionContratoReport as NovasoftLiquidacionContratoReport;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use App\Service\ServicioEmpleados\Report\Report\CertificadoIngresosReport as SeCertificadoIngresosReport;
use App\Service\Halcon\Report\Report\CertificadoIngresosReport as HalconCertificadoIngresosReport;
use App\Service\ServicioEmpleados\Report\Report\CertificadoLaboralReport as SeCertificadoLaboralReport;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Service\ServiceSubscriberInterface;


class ReportFactory implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Nomina $nomina
     * @return ReportInterface|SsrsReport
     */
    public function nomina(Nomina $nomina)
    {
        if ($nomina->isSourceNapi()) {
            return $this->getNapiReport(NapiNomina::class, $nomina);
        }
        [$noContrat, $consecLiq] = explode(',', $nomina->getSourceId());
        return $this->container->get(HalconReportFactory::class)
            ->nomina($noContrat, $consecLiq, $nomina->getUsuario());
    }

    /**
     * @param null|string|CertificadoLaboral $filter
     * @return SeCertificadoLaboralReport|HalconCertificadoLaboralReport|SsrsReport
     */
    public function certificadoLaboral($filter = null)
    {
        if (!$filter || is_string($filter)) {
            $report = $this->container->get(SeCertificadoLaboralReport::class);
            if ($filter) {
                $report->setIdentificacion($filter);
            }
        } else {
            if ($filter->isSourceNapi()) {
                return $this->getNapiReport(NapiCertificadoLaboral::class, $filter);
            }
            $numeroContrato = $filter->getSourceId();
            return $this->container->get(HalconReportFactory::class)
                ->certificadoLaboral($numeroContrato, $filter->getUsuario());
        }
        return $report;
    }

    /**
     * @param null|string|CertificadoIngresos $filter
     * @return HalconCertificadoIngresosReport|SsrsReport
     */
    public function certificadoIngresos($filter = null)
    {
        if (!$filter || is_string($filter)) {
            $report = $this->container->get(SeCertificadoLaboralReport::class);
            if ($filter) {
                $report->setIdentificacion($filter);
            }
        } else if ($filter->isSourceNapi()) {
            return $this->getNapiReport(NapiCertificadoIngresos::class, $filter);
        } else {
            [$empresaUsuario, $noContrat, $ano] = explode(',', $filter->getSourceId());
            return $this->container->get(HalconReportFactory::class)
                ->certificadoIngresos($empresaUsuario, $noContrat, $ano, $filter->getUsuario());
        }
        return $report;
    }

    /**
     * @param LiquidacionContrato $seLiqContrato
     * @return HalconLiquidacionContratoReport|SsrsReport
     */
    public function liquidacionContrato(LiquidacionContrato $seLiqContrato)
    {
        if ($seLiqContrato->isSourceNapi()) {
            return $this->getNapiReport(NapiLiquidacionContrato::class, $seLiqContrato);
        }
        [$noContrat, $liqDefini] = explode(',', $seLiqContrato->getSourceId());
        return $this->container->get(HalconReportFactory::class)
            ->liquidacionContrato($noContrat, $liqDefini, $seLiqContrato->getUsuario());
    }

    /**
     * @param ServicioEmpleadosReport $entity
     * @return ReportInterface
     */
    public function getReportByEntity(ServicioEmpleadosReport $entity)
    {
        $reportEntityClassName = lcfirst(preg_replace('/.+\\\\(\w+)$/', '$1', get_class($entity)));
        return $this->$reportEntityClassName($entity);
    }

    public static function getSubscribedServices()
    {
        return [
            NovasoftReportFactory::class,
            NapiReportFactory::class,
            HalconReportFactory::class,
            NovasoftEmpleadoService::class,
            SeCertificadoLaboralReport::class,
            HalconCertificadoIngresosReport::class,
            NapiCertificadoIngresosReport::class,
            SeCertificadoIngresosReport::class,
            EntityManagerInterface::class
        ];
    }

    private function getSsrsDb(ServicioEmpleadosReport $report)
    {
        return $this->container->get(NovasoftEmpleadoService::class)->getSsrsDb($report->getUsuario()->getIdentificacion());
    }

    private function getNapiReport($entityClass, $seReport)
    {
        $napiReport = $this->container
            ->get(EntityManagerInterface::class)->getRepository($entityClass)->find($seReport->getSourceId());
        if($napiReport) {
            return $this->container
                ->get(NapiReportFactory::class)->getReport($entityClass)->setReport($napiReport);
        }
        throw new NotFoundHttpException('Reporte no existe');
    }
}