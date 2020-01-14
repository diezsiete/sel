<?php


namespace App\Service\Halcon\Report;


use App\Service\Halcon\Report\Report\Nomina;
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
     * @return Nomina
     */
    public function nomina($noContrat, $consecLiq, $nitTercer)
    {
        return $this->container->get(Nomina::class)
            ->setNoContrat($noContrat)
            ->setConsecLiq($consecLiq)
            ->setNitTercer($nitTercer);
    }


    public static function getSubscribedServices()
    {
        return [
            Nomina::class
        ];
    }
}