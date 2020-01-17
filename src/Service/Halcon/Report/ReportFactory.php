<?php


namespace App\Service\Halcon\Report;



use App\Service\Halcon\Report\Report\CertificadoIngresosReport;
use App\Service\Halcon\Report\Report\CertificadoLaboralReport;
use App\Service\Halcon\Report\Report\NominaReport;
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
     * @param $nitTercer
     * @return NominaReport
     */
    public function nomina($noContrat, $consecLiq, $nitTercer)
    {
        return $this->container->get(NominaReport::class)
            ->setNoContrat($noContrat)
            ->setConsecLiq($consecLiq)
            ->setNitTercer($nitTercer);
    }

    /**
     * @param $ident
     * @param $numeroContrato
     * @return CertificadoLaboralReport
     */
    public function certificadoLaboral($ident, $numeroContrato)
    {
        return $this->container->get(CertificadoLaboralReport::class)
            ->setIdentificacion($ident)
            ->setNumeroContrato($numeroContrato);
    }

    /**
     * @param $usuario
     * @param $noContrat
     * @param $ano
     * @param $identificacion
     * @return CertificadoIngresosReport
     */
    public function certificadoIngresos($usuario, $noContrat, $ano, $identificacion)
    {
        return $this->container->get(CertificadoIngresosReport::class)
            ->setUsuario($usuario)
            ->setNoContrat($noContrat)
            ->setAno($ano)
            ->setIdentificacion($identificacion);
    }


    public static function getSubscribedServices()
    {
        return [
            NominaReport::class,
            CertificadoLaboralReport::class,
            CertificadoIngresosReport::class
        ];
    }
}