<?php


namespace App\Service\NovasoftSsrs;


use SSRS\Common\Credentials;
use SSRS\SSRSReport;

class NovasoftSsrs
{
    /**
     * @var ReportServer
     */
    private $reportServer;


    public function __construct(string $userName, string $password, string $url)
    {
        $this->reportServer = new ReportServer($userName, $password, $url);
    }
}