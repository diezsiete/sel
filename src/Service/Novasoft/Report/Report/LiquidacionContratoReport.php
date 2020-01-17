<?php


namespace App\Service\Novasoft\Report\Report;


use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\GenericImporter;
use App\Service\Novasoft\Report\Mapper\LiquidacionContratoMapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\Utils;
use DateTime;
use DateTimeInterface;
use SSRS\SSRSReport;

class LiquidacionContratoReport extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM701";


    protected $parameter_Ind_Fec = "1";
    protected $parameter_fFecIni = "2/1/2017";
    protected $parameter_fFecFin = "4/30/2017";
    protected $parameter_cod_conv = "%";
    protected $parameter_cod_cia = "%";
    protected $parameter_cod_suc = "%";
    protected $parameter_cod_cco = "%";
    protected $parameter_cod_cl1 = "%";
    protected $parameter_cod_cl2 = "%";
    protected $parameter_cod_cl3 = "%";
    protected $parameter_CodEmp;
    protected $parameter_Origen  = "H";
    /**
     * @var DateTimeInterface
     */
    private $fechaInicio;

    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, LiquidacionContratoMapper $mapper, GenericImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
        $this->parameter_fFecFin = $this->utils->dateFormatToday('m/t/Y');
    }

    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_CodEmp = $identificacion;
        return $this;
    }

    public function setParameterFechaInicio(DateTimeInterface $fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
        $this->parameter_fFecIni = $fechaInicio->format('m/d/Y');
        return $this;
    }

    public function setParameterFechaFin(DateTimeInterface $fechaFin)
    {
        $this->parameter_fFecFin = $fechaFin->format('m/t/Y');
        return $this;
    }

    public function getPdfFileName(): string
    {
        return 'nomina/liquidacion-contrato/' . $this->parameter_CodEmp . '-' . $this->fechaInicio->format('Ymd') . '.pdf';
    }
}