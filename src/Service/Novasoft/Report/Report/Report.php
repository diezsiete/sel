<?php


namespace App\Service\Novasoft\Report\Report;


use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\Importer;
use App\Service\Novasoft\Report\Mapper\Mapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\Utils;
use SSRS\RenderType\RenderAsCSV;
use SSRS\RenderType\RenderAsPDF;
use SSRS\SSRSReport;
use SSRS\SSRSReportException;
use SSRS\SSRSType\ExecutionInfo2;
use SSRS\SSRSType\PageCountModeEnum;
use SSRS\SSRSType\ParameterValue;
use SSRS\SSRSType\ReportParameterCollection;

abstract class Report
{
    protected $path = "";

    /**
     * @var SSRSReport
     */
    protected $SSRSReport;

    /**
     * @var ReportFormatter
     */
    protected $reportFormatter;

    /**
     * @var Mapper
     */
    protected $mapper;

    /**
     * @var string
     */
    protected $db;

    /**
     * @var Utils
     */
    protected $utils;

    /**
     * @var ReportParameterCollection
     */
    protected $reportParameters = null;

    protected $renderExtension;

    protected $renderMimeType;

    protected $renderEncoding;

    protected $renderWarnings;

    protected $renderStreamIds;

    /**
     * @var Importer
     */
    protected $importer;

    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, Mapper $mapper, Importer $importer)
    {
        $this->SSRSReport = $SSRSReport;
        $this->reportFormatter = $reportFormatter;
        $this->mapper = $mapper;
        $this->db = $configuracion->getSsrsDb()[0]->getNombre();
        $this->utils = $utils;
        $this->importer = $importer;
    }


    public function setDb(string $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * @return ReportParameterCollection
     * @throws SSRSReportException
     */
    public function getParameters()
    {
        if(!$this->reportParameters) {
            $this->reportParameters = $this->loadReport()->Parameters;
        }
        return $this->reportParameters;
    }

    /**
     * @return bool|string
     * @throws SSRSReportException
     */
    public function renderCSV()
    {
        $this->loadReport();
        $this->SSRSReport->setExecutionParameters2($this->getExecutionParameters());
        $csv = $this->render(new RenderAsCSV(), PageCountModeEnum::$Actual);
        return substr($csv, 3);
    }

    /**
     * @return array
     * @throws SSRSReportException
     */
    public function renderAssociative()
    {
        return $this->reportFormatter->csvToAssociative($this->renderCSV());
    }

    /**
     * @throws SSRSReportException
     */
    public function renderPdf()
    {
        $this->loadReport();
        $this->SSRSReport->setExecutionParameters2($this->getExecutionParameters());
        return $this->render(new RenderAsPDF(), PageCountModeEnum::$Actual);
    }

    public abstract function importPdf();


    /**
     * @return mixed
     * @throws SSRSReportException
     */
    public function renderMap()
    {
        return $this->reportFormatter->mapCsv($this->renderCSV(), $this->mapper);
    }

    public function importMap()
    {
        $this->importer->import($this->renderMap());
    }

    /**
     * @return ExecutionInfo2
     */
    public function getExecutionInfo(): ExecutionInfo2
    {
        return $this->SSRSReport->GetExecutionInfo2();
    }

    /**
     * @return object
     */
    public function getMiniExecutionInfo()
    {
        $executionInfo = $this->SSRSReport->GetExecutionInfo2();
        return (object)[
            'NumPages' => $executionInfo->NumPages,
            'PageCountMode' => $executionInfo->PageCountMode
        ];
    }


    /**
     * @return ExecutionInfo2
     * @throws SSRSReportException
     */
    protected function loadReport()
    {
        return $this->SSRSReport->LoadReport2($this->getPath());
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return "/" . $this->db . $this->path;
    }

    /**
     * Converts all the properties with name starting with parameter_ and converts them as ParameterValue for SSRSReport
     */
    protected function getExecutionParameters()
    {
        $vars = get_object_vars($this);
        $parameters = [];
        foreach($vars as $var_name => $var_value) {
            if(preg_match('/(^parameter_)(.+)/', $var_name, $matches)) {
                $normalizeMethod = 'normalizeParameter_' . $matches[2];
                $value = method_exists($this,$normalizeMethod) ? $this->$normalizeMethod() : $var_value;

                $parameterValue = new ParameterValue();
                $parameterValue->Name = $matches[2];
                $parameterValue->Value = $value;
                $parameters[] = $parameterValue;
            }
        }
        return $parameters;
    }

    /**
     * @param $renderType
     * @param $paginationMode
     * @return mixed
     */
    protected function render($renderType, $paginationMode)
    {
        return $this->SSRSReport->Render2($renderType, $paginationMode, $this->renderExtension,
            $this->renderMimeType, $this->renderEncoding,$this->renderWarnings,
            $this->renderStreamIds);
    }
}