<?php


namespace App\Service\Novasoft\Report;


use App\Service\Novasoft\Report\Report\NominaReport;
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
     * @param null $fechaInicio
     * @param null $fechaFin
     * @return NominaReport
     */
    public function getReporteNomina($identificacion = null, $fechaInicio = null, $fechaFin = null)
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
        return $reporteNomina;
    }


    public static function getSubscribedServices()
    {
        return [
            NominaReport::class
        ];
    }
}