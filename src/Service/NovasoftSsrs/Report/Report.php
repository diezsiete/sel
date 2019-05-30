<?php

namespace App\Service\NovasoftSsrs\Report;



use App\Service\NovasoftSsrs\ReportFormatter;
use App\Service\NovasoftSsrs\ReportServer;
use App\Service\Utils;
use App\Service\NovasoftSsrs\Mapper\GenericMapper;
use SSRS\SSRSType\ExecutionInfo2;
use SSRS\SSRSType\ParameterValue;


abstract class Report
{
    /**
     * @var ReportServer
     */
    protected $reportServer;

    /**
     * @var ExecutionInfo2
     */
    protected $executionInfo = null;

    /**
     * @var ReportFormatter
     */
    protected $reportFormatter;

    /**
     * @var string
     */
    protected $mapperClass = GenericMapper::class;

    /**
     * The path to the report in the server, each child must define it
     * @var string
     */
    protected $path = "";


    /**
     * Flag to avoid setting the execution parameters multiple times
     * @var bool
     */
    protected $executionParametersSetted = false;
    /**
     * @var Utils
     */
    protected $utils;

    protected abstract function getReportPath(): string;

    protected abstract function getMapperClass(): ?string;

    /**
     * Report constructor.
     * @param ReportServer $reportServer
     * @param ReportFormatter $reportFormatter
     */
    public function __construct(ReportServer $reportServer, ReportFormatter $reportFormatter, Utils $utils, $novasoftSsrsDb)
    {
        $this->reportServer = $reportServer;
        $this->reportFormatter = $reportFormatter;
        $this->utils = $utils;

        $this->path = $this->getReportPath();
        $this->mapperClass = $this->getMapperClass() ?? GenericMapper::class;

        $this->setDb($novasoftSsrsDb);
    }

    public function setDb($db)
    {
        $this->path = "/$db$this->path";
    }

    /**
     * @return mixed
     * @throws \SSRS\SSRSReportException
     */
    public function getParameters()
    {
        return $this->loadReport()->Parameters;
    }

    /**
     * @return bool|string
     * @throws \SSRS\SSRSReportException
     */
    public function renderCSV()
    {
        $this->loadReport();
        $this->setExecutionParameters();
        $csv = $this->reportServer->renderCSV();
        return $csv;
    }

    /**
     * @return array
     * @throws \SSRS\SSRSReportException
     */
    public function renderAssociative()
    {
        $this->loadReport();
        $this->setExecutionParameters();
        return $this->reportFormatter->csvToAssociative($this->reportServer->renderCSV());
    }

    /**
     * @return mixed
     * @throws \SSRS\SSRSReportException
     */
    public function renderMap()
    {
        return $this->reportFormatter->mapCsv($this->renderCSV(), $this->getMapperInstance());
    }

    /**
     * @throws \SSRS\SSRSReportException
     */
    public function renderPdf()
    {
        $this->loadReport();
        $this->setExecutionParameters();
        return $this->reportServer->renderPdf();
    }


    /**
     * @return ExecutionInfo2
     * @throws \SSRS\SSRSReportException
     */
    protected function loadReport()
    {
        if($this->executionInfo === null) {
            $this->executionInfo = $this->reportServer->loadReport($this->path);
        }
        return $this->executionInfo;
    }

    /**
     * Converts all the properties with name starting with parameter_ and sets them as parameters to the report
     */
    public function setExecutionParameters()
    {
        $vars = get_object_vars($this);
        $parameters = [];
        foreach($vars as $var_name => $var_value) {
            if(preg_match('/(^parameter_)(.+)/', $var_name, $matches)) {
                //$parameters[$matches[2]] = $var_value;
                $parameterValue = new ParameterValue();
                $parameterValue->Name = $matches[2];
                $parameterValue->Value = $var_value;
                $parameters[] = $parameterValue;
            }
        }
        $this->reportServer->setExecutionParameters($parameters);
    }

    /**
     * @return mixed
     */
    protected function getMapperInstance()
    {
        return new $this->mapperClass();
    }
}