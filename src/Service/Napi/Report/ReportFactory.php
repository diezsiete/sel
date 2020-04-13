<?php

namespace App\Service\Napi\Report;

use App\Service\Napi\Report\Report\NominaReport;
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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param $entityName
     * @return Report|SsrsReport
     */
    public function getReport($entityName)
    {
        $reportName = __NAMESPACE__ . '\\Report\\'.$entityName . 'Report';
        return $this->container->get($reportName);
    }

    public static function getSubscribedServices()
    {
        return [
            NominaReport::class
        ];
    }


}