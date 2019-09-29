<?php

namespace App\Service\NovasoftSsrs;



use SSRS\Common\Credentials;
use SSRS\RenderType\RenderAsCSV;
use SSRS\RenderType\RenderAsPDF;
use SSRS\SSRSReport;
use SSRS\SSRSReportException;
use SSRS\SSRSType\ExecutionInfo2;
use SSRS\SSRSType\PageCountModeEnum;
use SSRS\SSRSType\ParameterValue;

class ReportServer
{
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $url;

    /**
     * @var SSRSReport
     */
    private $ssrsReport = null;


    private $renderExtension;

    private $renderMimeType;

    private $renderEncoding;

    private $renderWarnings;

    private $renderStreamIds;

    /**
     * @var ExecutionInfo2
     */
    private $currentReport;


    public function __construct(string $username, string $password, string $url)
    {
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
    }

    /**
     * @param $reportPath
     * @return ExecutionInfo2
     * @throws SSRSReportException
     */
    public function loadReport($reportPath)
    {
        if(!$this->currentReport || $this->currentReport->ReportPath !== $reportPath) {
            $this->currentReport = $this->connect()->LoadReport2($reportPath);
        }
        return $this->currentReport;
    }


    public function renderCSV()
    {
        $csv = $this->render(new RenderAsCSV(), PageCountModeEnum::$Actual);
        return substr($csv, 3);
    }

    public function renderPdf()
    {
        return $this->render(new RenderAsPDF(), PageCountModeEnum::$Actual);
    }

    /**
     * @param ParameterValue[] $parameters
     */
    public function setExecutionParameters($parameters)
    {
        $this->ssrsReport->setExecutionParameters2($parameters);
    }

    /**
     * @return SSRSReport
     * @throws SSRSReportException
     */
    private function connect()
    {
        if($this->ssrsReport === null) {
            $this->ssrsReport = new SSRSReport(new Credentials($this->username, $this->password), $this->url);
        }
        return $this->ssrsReport;
    }

    /**
     * @param $renderType
     * @param $paginationMode
     * @return mixed
     */
    private function render($renderType, $paginationMode)
    {
        return $this->ssrsReport->Render2($renderType, $paginationMode, $this->renderExtension,
            $this->renderMimeType, $this->renderEncoding,$this->renderWarnings,
            $this->renderStreamIds);
    }
}