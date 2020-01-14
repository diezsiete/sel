<?php


namespace App\Service\ServicioEmpleados\Report;


use App\Entity\ServicioEmpleados\Nomina;
use App\Repository\Novasoft\Report\Nomina\NominaRepository as NovasoftNominaRepo;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
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
    public function getReporteNomina(Nomina $nomina)
    {
        if($nomina->isSourceNovasoft()) {
            $nominaNovasoft = $this->container->get(NovasoftNominaRepo::class)->find($nomina->getSourceId());
            return $this->container->get(NovasoftReportFactory::class)
                ->nomina(
                    $nomina->getUsuario()->getIdentificacion(),
                    $nominaNovasoft->getFecha(),
                    $nominaNovasoft->getFecha(),
                    $this->container->get(NovasoftEmpleadoService::class)->getSsrsDb($nomina->getUsuario()->getIdentificacion())
                );
        } else {
            list($noContrat, $consecLiq, $nitTercer) = explode(",", $nomina->getSourceId());
            return $this->container->get(HalconReportFactory::class)
                ->nomina($noContrat, $consecLiq, $nitTercer);
        }
    }


    public static function getSubscribedServices()
    {
        return [
            NovasoftNominaRepo::class,
            NovasoftReportFactory::class,
            HalconReportFactory::class,
            NovasoftEmpleadoService::class
        ];
    }
}