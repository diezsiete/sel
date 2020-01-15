<?php


namespace App\Service\ServicioEmpleados\Report;


use App\Entity\ServicioEmpleados\CertificadoLaboral;
use App\Entity\ServicioEmpleados\Nomina;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Repository\Novasoft\Report\Nomina\NominaRepository as NovasoftNominaRepo;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\ReportFactory as NovasoftReportFactory;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use App\Service\ServicioEmpleados\Report\Report\CertificadoLaboral as CertificadoLaboralReport;
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
            list($noContrat, $consecLiq, $nitTercer) = explode(",", $nomina->getSourceId());
            return $this->container->get(HalconReportFactory::class)
                ->nomina($noContrat, $consecLiq, $nitTercer);
        }
    }

    /**
     * @param null|string|CertificadoLaboral $filter
     * @return CertificadoLaboral|mixed
     */
    public function certificadoLaboral($filter = null)
    {
        if(!$filter || is_string($filter)) {
            $report = $this->container->get(CertificadoLaboralReport::class);
            if ($filter) {
                $report->setIdentificacion($filter);
            }
        } else {
            if($filter->isSourceNovasoft()) {
                return $this->container->get(NovasoftReportFactory::class)
                    ->certificadoLaboral($filter->getSourceId(), $this->getSsrsDb($filter));
            } else {
                list($ident, $numeroContrato) = explode(",", $filter->getSourceId());
                return $this->container->get(HalconReportFactory::class)
                    ->certificadoLaboral($ident, $numeroContrato);
            }
        }
        return $report;
    }


    public static function getSubscribedServices()
    {
        return [
            NovasoftNominaRepo::class,
            NovasoftReportFactory::class,
            HalconReportFactory::class,
            NovasoftEmpleadoService::class,
            CertificadoLaboralReport::class
        ];
    }

    private function getSsrsDb(ServicioEmpleadosReport $report)
    {
        return $this->container->get(NovasoftEmpleadoService::class)->getSsrsDb($report->getUsuario()->getIdentificacion());
    }
}