<?php


namespace App\Service\NovasoftSsrs;


use App\Service\NovasoftSsrs\Report\ReportNom204;
use App\Service\NovasoftSsrs\Report\ReportNom701;
use App\Service\NovasoftSsrs\Report\ReportNom92117;
use App\Service\NovasoftSsrs\Report\ReportNom932;
use App\Service\NovasoftSsrs\Report\ReportNom936;

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
     * @var ReportNom701
     */
    private $reportNom701;

    /**
     * @var ReportNom936
     */
    private $reportNom936;

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
     * @required
     * @param ReportNom701 $reportNom701
     */
    public function setReportNom701(ReportNom701 $reportNom701)
    {
        $this->reportNom701 = $reportNom701;
    }

    /**
     * @required
     */
    public function setReportNom936(ReportNom936 $reportNom936)
    {
        $this->reportNom936 = $reportNom936;
    }

    /**
     * @return ReportNom204
     */
    public function getReportNom204(): ReportNom204
    {
        return $this->reportNom204;
    }

    /**
     * @return ReportNom701
     */
    public function getReportNom701()
    {
        return $this->reportNom701;
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

    /**
     * @return ReportNom936
     */
    public function getReportNom936(): ReportNom936
    {
        return $this->reportNom936;
    }
}