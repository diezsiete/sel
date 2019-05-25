<?php


namespace App\Service\NovasoftSsrs;


use App\Service\NovasoftSsrs\Report\ReportNom204;
use App\Service\NovasoftSsrs\Report\ReportNom92117;
use App\Service\NovasoftSsrs\Report\ReportNom932;
use SSRS\Common\Credentials;
use SSRS\SSRSReport;

class NovasoftSsrs
{
    /**
     * @var ReportNom204
     */
    private $reportNom204;
    /**
     * @var ReportNom932
     */
    private $reportNom932;

    /**
     * @var ReportNom92117
     */
    private $reportNom92117;

    /**
     * @required
     * @param ReportNom204 $reportNom204
     */
    public function setReportNom204(ReportNom204 $reportNom204)
    {
        $this->reportNom204 = $reportNom204;
    }
    /**
     * @required
     * @param ReportNom932 $reportNom932
     */
    public function setReportNom932(ReportNom932 $reportNom932)
    {
        $this->reportNom932 = $reportNom932;
    }
    /**
     * @required
     * @param ReportNom92117 $reportNom92117
     */
    public function setReportNom92117(ReportNom92117 $reportNom92117)
    {
        $this->reportNom92117 = $reportNom92117;
    }

    /**
     * @return ReportNom204
     */
    public function getReportNom204(): ReportNom204
    {
        return $this->reportNom204;
    }

    /**
     * @return ReportNom932
     */
    public function getReportNom932(): ReportNom932
    {
        return $this->reportNom932;
    }

    /**
     * @return ReportNom92117
     */
    public function getReportNom92117(): ReportNom92117
    {
        return $this->reportNom92117;
    }
}