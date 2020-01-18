<?php


namespace App\Service\Halcon\Report;



use App\Entity\Main\Usuario;
use App\Service\Halcon\Report\Report\CertificadoIngresosReport;
use App\Service\Halcon\Report\Report\CertificadoLaboralReport;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport;
use App\Service\Halcon\Report\Report\NominaReport;
use App\Service\Halcon\Report\Report\Report;
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
     * @param $noContrat
     * @param $consecLiq
     * @param Usuario $usuario
     * @return NominaReport
     */
    public function nomina($noContrat, $consecLiq, Usuario $usuario)
    {
        return $this->container->get(NominaReport::class)
            ->setNoContrat($noContrat)
            ->setConsecLiq($consecLiq)
            ->setUsuario($usuario);
    }

    /**
     * @param $numeroContrato
     * @param Usuario $usuario
     * @return CertificadoLaboralReport
     */
    public function certificadoLaboral($numeroContrato, Usuario $usuario)
    {
        return $this->container->get(CertificadoLaboralReport::class)
            ->setNumeroContrato($numeroContrato)
            ->setUsuario($usuario);
    }

    /**
     * @param $empresaUsuario
     * @param $noContrat
     * @param $ano
     * @param Usuario $usuario
     * @return CertificadoIngresosReport
     */
    public function certificadoIngresos($empresaUsuario, $noContrat, $ano, Usuario $usuario)
    {
        return $this->container->get(CertificadoIngresosReport::class)
            ->setEmpresaUsuario($empresaUsuario)
            ->setNoContrat($noContrat)
            ->setAno($ano)
            ->setUsuario($usuario);
    }

    /**
     * @param $noContrat
     * @param $liqDefini
     * @param Usuario $usuario
     * @return LiquidacionContratoReport
     */
    public function liquidacionContrato($noContrat, $liqDefini, Usuario $usuario)
    {
        return $this->container->get(LiquidacionContratoReport::class)
            ->setNoContrat($noContrat)
            ->setLiqDefini($liqDefini)
            ->setUsuario($usuario);
    }

    /**
     * @param $entityName
     * @return Report
     */
    public function getReport($entityName)
    {
        $reportName = __NAMESPACE__ . '\\Report\\'.$entityName . 'Report';
        return $this->container->get($reportName);
    }


    public static function getSubscribedServices()
    {
        return [
            NominaReport::class,
            CertificadoLaboralReport::class,
            CertificadoIngresosReport::class,
            LiquidacionContratoReport::class
        ];
    }
}