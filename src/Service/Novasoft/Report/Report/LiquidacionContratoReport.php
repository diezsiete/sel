<?php


namespace App\Service\Novasoft\Report\Report;


use App\Entity\Main\Usuario;
use App\Entity\Novasoft\Report\LiquidacionContrato;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\LiquidacionContratoImporter;
use App\Service\Novasoft\Report\Mapper\LiquidacionContratoMapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\Utils;
use DateTime;
use DateTimeInterface;
use SSRS\SSRSReport;

/**
 * Class LiquidacionContratoReport
 * @package App\Service\Novasoft\Report\Report
 * @deprecated
 */
class LiquidacionContratoReport extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM701";
    //RS_rhh_Repnom701
        // rs_gen_empresa
        // rs_gen_ccosto
        // rs_gen_Sucur
        // rs_gen_cl1
        // rs_gen_cl2
        // rs_gen_cl3
        // rs_rhh_tipliq
        // rs_rhh_ConsEmplea
        // rs_rhh_ConsEmplea
        // rs_gen_Cia
        //SELECT     CONVERT(DATETIME, substring(val_var,7,4)+substring(val_var,4,2)+substring(val_var,1,2)) AS val_var
        //FROM         nom_vargen
        //WHERE     (num_var = 1)
        //rs_rhh_Convenio_Usuario


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
                                Utils $utils, LiquidacionContratoMapper $mapper, LiquidacionContratoImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
        $this->parameter_fFecFin = $this->utils->dateFormatToday('m/t/Y');
    }

    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_CodEmp = $identificacion;
        return $this;
    }

    public function setParameterFechaInicio(?DateTimeInterface $fechaInicio = null)
    {
        if(!$fechaInicio) {
            $fechaInicio = DateTime::createFromFormat('m/d/Y', '2/1/2017');
        }
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
        return 'novasoft/liquidacion-contrato/' . $this->parameter_CodEmp . '-' . $this->fechaInicio->format('Ymd') . '.pdf';
    }

    /**
     * @param Usuario $usuario
     * @return $this
     */
    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
        $this->parameter_CodEmp = $usuario->getIdentificacion();
        return $this;
    }

    /**
     * @param LiquidacionContrato $liquidacionContrato
     * @return LiquidacionContratoReport
     */
    public function setParametersByEntity($liquidacionContrato)
    {
        $this
            ->setParameterCodigoEmpleado($liquidacionContrato->getUsuario()->getIdentificacion())
            ->setParameterFechaInicio($liquidacionContrato->getFechaIngreso())
            ->setParameterFechaFin($liquidacionContrato->getFechaRetiro())
            ->setDb($liquidacionContrato->getSsrsDb() ? $liquidacionContrato->getSsrsDb() : $this->db);
        return $this;
    }
}