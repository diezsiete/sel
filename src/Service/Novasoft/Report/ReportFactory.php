<?php

namespace App\Service\Novasoft\Report;

use App\Entity\Main\Convenio;
use App\Entity\Novasoft\Report\Nomina\Nomina;
use App\Service\Novasoft\Report\Report\CertificadoIngresosReport;
use App\Service\Novasoft\Report\Report\CertificadoLaboralReport;
use App\Service\Novasoft\Report\Report\LiquidacionContratoReport;
use App\Service\Novasoft\Report\Report\LiquidacionNominaReport;
use App\Service\Novasoft\Report\Report\NominaReport;
use App\Service\Novasoft\Report\Report\Report;
use App\Service\Novasoft\Report\Report\TrabajadoresActivosReport;
use App\Service\Novasoft\Report\Report\Clientes\ReportFactory as ClientesReportFactory;
use DateTimeInterface;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * Class ReportFactory
 * @package App\Service\Novasoft\Report
 * @deprecated
 */
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
     * @param string|Nomina $identificacion
     * @param null|DateTimeInterface|string $fechaInicio si no se asigna se toma 2/1/2017
     * @param null|DateTimeInterface $fechaFin
     * @param null|string $ssrsdb
     * @return NominaReport
     */
    public function nomina($identificacion = null, $fechaInicio = null, $fechaFin = null, $ssrsdb = null)
    {
        $reporteNomina = $this->container->get(NominaReport::class);
        if($identificacion) {
            if(is_object($identificacion)) {
                $reporteNomina->setParametersByEntity($identificacion, $fechaInicio);
                $fechaInicio = null;
            } else {
                $reporteNomina->setParameterCodigoEmpleado($identificacion);
            }
        }
        if($fechaInicio) {
            $reporteNomina->setParameterFechaInicio($fechaInicio);
        }
        if($fechaFin) {
            $reporteNomina->setParameterFechaFin($fechaFin);
        }

        if($ssrsdb) {
            $reporteNomina->setDb($ssrsdb);
        }

        return $reporteNomina;
    }

    /**
     * @param Convenio $convenio
     * @param DateTimeInterface|null $fecha
     * @return TrabajadoresActivosReport
     */
    public function trabajadoresActivos(Convenio $convenio, ?DateTimeInterface $fecha = null)
    {
        $report = $this->container->get(TrabajadoresActivosReport::class);

        return $report
                ->setConvenio($convenio)
                ->setFecha($fecha);
    }

    /**
     * @param Convenio $convenio
     * @param DateTimeInterface $fechaInicio
     * @param DateTimeInterface $fechaFin
     * @return LiquidacionNominaReport
     */
    public function liquidacionNomina(Convenio $convenio, DateTimeInterface $fechaInicio, DateTimeInterface $fechaFin)
    {
        $report = $this->container->get(LiquidacionNominaReport::class);

        return $report
                ->setFechaInicial($fechaInicio)
                ->setFechaFinal($fechaFin)
                ->setConvenio($convenio);
    }

    /**
     * @param string|null $ident
     * @param string|null $ssrsdb
     * @return CertificadoLaboralReport
     */
    public function certificadoLaboral(?string $ident = null, $ssrsdb = null)
    {
        $report = $this->container->get(CertificadoLaboralReport::class);
        if($ident) {
            $report->setParameterCodigoEmpleado($ident);
        }
        if($ssrsdb) {
            $report->setDb($ssrsdb);
        }
        return $report;
    }

    /**
     * @param int $ano
     * @param string|null $ident
     * @param string|null $ssrsDb
     * @return CertificadoIngresosReport
     */
    public function certificadoIngresos($ano = 2018, ?string $ident = null, string $ssrsDb = null)
    {
        $report = $this->container->get(CertificadoIngresosReport::class);
        $report->setParameterAno($ano);
        if($ident) {
            $report->setParameterCodigoEmpleado($ident);
        }
        if($ssrsDb) {
            $report->setDb($ssrsDb);
        }
        return $report;
    }

    /**
     * @param string|null $identificacion
     * @param DateTimeInterface|null $fechaIngreso
     * @param DateTimeInterface|null $fechaRetiro
     * @param string|null $ssrsDb
     * @return LiquidacionContratoReport
     */
    public function liquidacionContrato(?string $identificacion = null, ?DateTimeInterface $fechaIngreso = null,
                                        ?DateTimeInterface $fechaRetiro = null, ?string $ssrsDb = null)
    {
        $report = $this->container->get(LiquidacionContratoReport::class);
        if($identificacion) {
            $report->setParameterCodigoEmpleado($identificacion);
        }
        if($fechaIngreso) {
            $report->setParameterFechaInicio($fechaIngreso);
        }
        if($fechaRetiro) {
            $report->setParameterFechaFin($fechaRetiro);
        }
        if($ssrsDb) {
            $report->setDb($ssrsDb);
        }
        return $report;
    }

    /**
     * @param $entityName
     * @return Report|CertificadoIngresosReport|NominaReport
     */
    public function getReport($entityName)
    {
        $reportName = __NAMESPACE__ . '\\Report\\'.$entityName . 'Report';
        return $this->container->get($reportName);
    }

    /**
     * @return ClientesReportFactory
     */
    public function clientes()
    {
        return $this->container->get(ClientesReportFactory::class);
    }

    public static function getSubscribedServices()
    {
        return [
            NominaReport::class,
            TrabajadoresActivosReport::class,
            LiquidacionNominaReport::class,
            CertificadoLaboralReport::class,
            CertificadoIngresosReport::class,
            LiquidacionContratoReport::class,
            ClientesReportFactory::class
        ];
    }


}