<?php


namespace App\Service\Novasoft\Report\Report;


use App\Entity\Novasoft\Report\CertificadoLaboral;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\GenericImporter;
use App\Service\Novasoft\Report\Mapper\CertificadoLaboralMapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\Utils;
use SSRS\SSRSReport;
use SSRS\SSRSReportException;

/**
 * Class CertificadoLaboralReport
 * @package App\Service\Novasoft\Report\Report
 */
class CertificadoLaboralReport extends Report
{

    protected $path = "/ReportesWeb/NOM/NOM932";

    /**
     * Identificación del empleado
     * @var string
     */
    protected $parameter_cod_emp;


    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, CertificadoLaboralMapper $mapper, GenericImporter $importer)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer);
    }

    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_cod_emp = $identificacion;
        return $this;
    }

    /**
     * @return CertificadoLaboral[]
     * @throws SSRSReportException
     */
    public function renderMap()
    {
        $csvAssociative = $this->reportFormatter->csvColsSplittedToAssociative($this->renderCSV());
        return $this->reportFormatter->mapCsv($csvAssociative, $this->mapper);
    }

    public function getFileNamePdf($asArray = false)
    {
        // TODO: Implement getFileNamePdf() method.
    }


}