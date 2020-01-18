<?php


namespace App\Service\ServicioEmpleados\Report;


use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\CertificadoLaboral;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\Nomina;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Repository\Novasoft\Report\Nomina\NominaRepository as NovasoftNominaRepo;
use App\Service\Halcon\Report\Report\CertificadoLaboralReport as HalconCertificadoLaboralReport;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport as HalconLiquidacionContratoReport;
use App\Service\Novasoft\Report\Report\LiquidacionContratoReport as NovasoftLiquidacionContratoReport;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\Report\CertificadoLaboralReport as NovasoftCertificadoLaboralReport;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use App\Service\ServicioEmpleados\Report\Report\CertificadoIngresosReport as SeCertificadoIngresosReport;
use App\Service\Halcon\Report\Report\CertificadoIngresosReport as HalconCertificadoIngresosReport;
use App\Service\Novasoft\Report\Report\CertificadoIngresosReport as NovasoftCertificadoIngresosReport;
use App\Service\ServicioEmpleados\Report\Report\CertificadoLaboralReport as SeCertificadoLaboralReport;
use Psr\Container\ContainerInterface;
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
     * @return ReportInterface
     */
    public function nomina(Nomina $nomina)
    {
        if($nomina->isSourceNovasoft()) {
            $nominaNovasoft = $this->container->get(NovasoftNominaRepo::class)->find($nomina->getSourceId());
            return $this->container->get(NovasoftReportFactory::class)
                ->nomina(
                    $nomina->getUsuario()->getIdentificacion(),
                    $nominaNovasoft->getFecha(),
                    $nominaNovasoft->getFecha(),
                    $this->getSsrsDb($nomina)
                );
        } else {
            list($noContrat, $consecLiq) = explode(",", $nomina->getSourceId());
            return $this->container->get(HalconReportFactory::class)
                ->nomina($noContrat, $consecLiq, $nomina->getUsuario());
        }
    }

    /**
     * @param null|string|CertificadoLaboral $filter
     * @return SeCertificadoLaboralReport|HalconCertificadoLaboralReport|NovasoftCertificadoLaboralReport
     */
    public function certificadoLaboral($filter = null)
    {
        if(!$filter || is_string($filter)) {
            $report = $this->container->get(SeCertificadoLaboralReport::class);
            if ($filter) {
                $report->setIdentificacion($filter);
            }
        } else {
            if($filter->isSourceNovasoft()) {
                return $this->container->get(NovasoftReportFactory::class)
                    ->certificadoLaboral($filter->getUsuario()->getIdentificacion(), $this->getSsrsDb($filter));
            } else {
                $numeroContrato = $filter->getSourceId();
                return $this->container->get(HalconReportFactory::class)
                    ->certificadoLaboral($numeroContrato, $filter->getUsuario());
            }
        }
        return $report;
    }

    /**
     * @param null|string|CertificadoIngresos $filter
     * @return SeCertificadoIngresosReport|HalconCertificadoIngresosReport|NovasoftCertificadoIngresosReport
     */
    public function certificadoIngresos($filter = null)
    {
        if(!$filter || is_string($filter)) {
            $report = $this->container->get(SeCertificadoLaboralReport::class);
            if ($filter) {
                $report->setIdentificacion($filter);
            }
        } else {
            if($filter->isSourceNovasoft()) {
                list($ano, $ident) = explode(",", $filter->getSourceId());
                return $this->container->get(NovasoftReportFactory::class)
                    ->certificadoIngresos($ano, $ident, $this->getSsrsDb($filter));
            } else {

                list($usuario, $noContrat, $ano, $identificacion) = explode(",", $filter->getSourceId());
                return $this->container->get(HalconReportFactory::class)
                    ->certificadoIngresos($usuario, $noContrat, $ano, $identificacion);
            }
        }
        return $report;
    }

    /**
     * @param LiquidacionContrato $liquidacionContrato
     * @return HalconLiquidacionContratoReport|NovasoftLiquidacionContratoReport
     */
    public function liquidacionContrato(LiquidacionContrato $liquidacionContrato)
    {
        if($liquidacionContrato->isSourceNovasoft()) {
            list($ano, $ident) = explode(",", $liquidacionContrato->getSourceId());
            return $this->container->get(NovasoftReportFactory::class)
                ->liquidacionContrato($ano, $ident, $this->getSsrsDb($liquidacionContrato));
        } else {
            list($noContrat, $liqDefini) = explode(",", $liquidacionContrato->getSourceId());
            return $this->container->get(HalconReportFactory::class)
                ->liquidacionContrato($noContrat, $liqDefini, $liquidacionContrato->getUsuario());
        }
    }


    public static function getSubscribedServices()
    {
        return [
            NovasoftNominaRepo::class,
            NovasoftReportFactory::class,
            HalconReportFactory::class,
            NovasoftEmpleadoService::class,
            SeCertificadoLaboralReport::class,
            HalconCertificadoIngresosReport::class,
            SeCertificadoIngresosReport::class
        ];
    }

    private function getSsrsDb(ServicioEmpleadosReport $report)
    {
        return $this->container->get(NovasoftEmpleadoService::class)->getSsrsDb($report->getUsuario()->getIdentificacion());
    }
}