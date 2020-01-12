<?php


namespace App\Service\Novasoft\Report;


use App\Entity\Main\Convenio;
use App\Service\Novasoft\Report\Report\LiquidacionNominaReport;
use App\Service\Novasoft\Report\Report\NominaReport;
use App\Service\Novasoft\Report\Report\TrabajadoresActivosReport;
use DateTimeInterface;
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
     * @param $identificacion
     * @param null|DateTimeInterface $fechaInicio si no se asigna se toma 2/1/2017
     * @param null|DateTimeInterface $fechaFin
     * @param null|string $ssrsdb
     * @return NominaReport
     */
    public function getReporteNomina($identificacion = null, $fechaInicio = null, $fechaFin = null, $ssrsdb = null)
    {
        $reporteNomina = $this->container->get(NominaReport::class);
        if($identificacion) {
            $reporteNomina->setParameterCodigoEmpleado($identificacion);
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


    public static function getSubscribedServices()
    {
        return [
            NominaReport::class,
            TrabajadoresActivosReport::class,
            LiquidacionNominaReport::class
        ];
    }
}