<?php


namespace App\Service\Halcon\Report;



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

    public function certificadoLaboral($ident, $numeroContrato)
    {
        return $this->container->get(CertificadoLaboralReport::class)
            ->setIdentificacion($ident)
            ->setNumeroContrato($numeroContrato);
    }


    public static function getSubscribedServices()
    {
        return [
            NominaReport::class,
            CertificadoLaboralReport::class
        ];
    }
}