<?php
/**
 * Reporte datos certificado laboral
 */

namespace App\Service\Novasoft\Report\Report;


use App\Entity\Novasoft\Report\CertificadoIngresos;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\GenericImporter;
use App\Service\Novasoft\Report\Mapper\CertificadoIngresosMapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\Utils;
use DateTime;
use SSRS\SSRSReport;
use SSRS\SSRSReportException;


class CertificadoIngresosReport extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM921_17";

    /**
     * @var string
     */
    protected $parameter_Fec_ini = '1/1/2017';
    /**
     * @var string
     */
    protected $parameter_Fec_fin = '12/31/2017';
    /**
     * @var string
     */
    protected $parameter_Cod_Conv = "%";
    /**
     * @var string
     */
    protected $parameter_cod_cia = "%";
    /**
     * @var string
     */
    protected $parameter_cod_suc = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cco = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla1 = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla2 = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla3 = "%";
    /**
     * @var
     */
    protected $parameter_fec_exp;
    /**
     * @var string
     */
    protected $parameter_Ccod_emp = "%";
    /**
     * @var string
     */
    private $ano;

    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, CertificadoIngresosMapper $mapper, GenericImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
        $this->parameter_fec_exp = (new DateTime())->format('m/d/Y');
    }

    /**
     * @param string $ano
     * @return CertificadoIngresosReport
     */
    public function setParameterAno(string $ano)
    {
        $this->ano = $ano;
        $pattern = '/(.+\/)\d+$/i';
        $replacement = '${1}' . $ano;
        $this->parameter_Fec_ini = preg_replace($pattern, $replacement, $this->parameter_Fec_ini);
        $this->parameter_Fec_fin = preg_replace($pattern, $replacement, $this->parameter_Fec_fin);
        return $this;
    }

    /**
     * @param $identificacion
     * @return $this
     */
    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_Ccod_emp = $identificacion;
        return $this;
    }

    /**
     * @return CertificadoIngresos|null
     * @throws SSRSReportException
     */
    public function renderMap()
    {
        $csvAssociative = $this->reportFormatter->csvContSplittedToAssociative($this->renderCSV());
        $report = $this->reportFormatter->mapCsv($csvAssociative, $this->mapper);
        return $report ? $report[0] : null;
    }

    public function renderAssociative()
    {
        return $this->reportFormatter->csvContSplittedToAssociative($this->renderCSV());
    }

    public function getPdfFileName(): string
    {
        return 'nomina/certificado-ingresos/' . $this->parameter_Ccod_emp . '-'.$this->ano.'.pdf';
    }
}