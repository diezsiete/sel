<?php

namespace App\Service\Napi\Report;


use App\Service\Napi\Report\Report\CertificadoLaboralReport;
use App\Service\Napi\Report\Report\NominaReport;
use App\Service\Utils\Symbol;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * Class ReportFactory
 * @package App\Service\Napi\Report
 */
class ReportFactory implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Symbol
     */
    private $utilSymbol;

    public function __construct(ContainerInterface $container, Symbol $utilSymbol)
    {
        $this->container = $container;
        $this->utilSymbol = $utilSymbol;
    }

    /**
     * @param $entityName
     * @return Report|SsrsReport
     */
    public function getReport($entityName)
    {
        $entityName = $this->utilSymbol->removeNamespaceFromClassName($entityName);
        $reportName = __NAMESPACE__ . '\\Report\\'.$entityName . 'Report';
        return $this->container->get($reportName);
    }

    public static function getSubscribedServices()
    {
        return [
            NominaReport::class,
            CertificadoLaboralReport::class
        ];
    }


}