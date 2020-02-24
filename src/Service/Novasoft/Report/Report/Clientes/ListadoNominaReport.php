<?php


namespace App\Service\Novasoft\Report\Report\Clientes;


use App\Entity\Main\Convenio;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\Clientes\ListadoNominaImporter;
use App\Service\Novasoft\Report\Mapper\Clientes\ListadoNomina\ListadoNominaMapper;
use App\Service\Novasoft\Report\Report\Report;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\Utils;
use DateTimeInterface;
use SSRS\SSRSReport;

/**
 * Class ListadoNominaReport
 * @package App\Service\Novasoft\Report\Report\Clientes
 * @method ListadoNomina[] renderMap()
 */
class ListadoNominaReport extends Report
{

    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, ListadoNominaMapper $mapper, ListadoNominaImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
    }

    protected $path = "/ReportesWeb/NOM/NOMU201C";
    // usr_sp_rhh_RepDetPTA
        // rs_gen_empresa
        // rs_rhh_tipliq
        /* DECLARE @rotulo VARCHAR(200);
           exec sp_gen_RotuloCad 1,@rotulo OUTPUT
           SELECT @rotulo */
        /* select '%' AS cod_conv, 'TODOS' AS nom_conv
            union all
            select cod_conv, cod_conv+' - '+nom_conv AS nom_conv
            from rhh_convenio */
        // rs_gen_Sucur
        // rs_gen_ccosto
        // rs_gen_cl1
        // rs_gen_cl2
        // rs_gen_cl3

    //=IIf(Fields!Nat_liq.Value = 1, "DEVENGO", IIF(Fields!Nat_liq.Value = 2, "DEDUCCION",IIF(Fields!Nat_liq.Value=3,"NETOS",IIF(Fields!Nat_liq.Value=4,"APORTES EMPLEADOR",IIF(Fields!Nat_liq.Value=5,"BASES","PROVISIONES/PARAFISCALES")))))

    /**
     * @var string Tipo Liquidación
     *  "%": <TODOS> (restringida)
     *  "0 ": NO APLICA (restringida)
     *  "01": Liquidación de Nómina
     *  "02": Prima de Servicios
     *  "04": Liquidacion de Contrato
     *  ...
     */
    protected $parameter_TipLiq = "01";

    /**
     * @var string
     * "0": Fecha Liquidación
     * "1": Fecha Corte (creo que este no se usa)
     */
    protected $parameter_ind_fec = "0";

    /**
     * @var DateTimeInterface Fecha inicial
     */
    protected $parameter_FecIni;

    /**
     * @var DateTimeInterface Fecha final
     */
    protected $parameter_FecFin;

    /**
     * @var string codigo convenio
     */
    protected $parameter_cod_conv = "%";

    /**
     * @var string
     */
    protected $parameter_CodEmp = "%";

    /**
     * @var string
     * "H": historico
     * "P": prenomina (creo que este no se usa)
     */
    protected $parameter_Origen = "H";

    /**
     * @param DateTimeInterface $fechaInicio
     * @return ListadoNominaReport
     */
    public function setFechaInicial(DateTimeInterface $fechaInicio)
    {
        $this->parameter_FecIni = $fechaInicio;
        return $this;
    }

    /**
     * @param DateTimeInterface $fechaFin
     * @return ListadoNominaReport
     */
    public function setFechaFinal(DateTimeInterface $fechaFin)
    {
        $this->parameter_FecFin = $fechaFin;
        return $this;
    }

    /**
     * @param Convenio $convenio
     * @return ListadoNominaReport
     */
    public function setConvenio($convenio)
    {
        $this->parameter_cod_conv = is_object($convenio) ? $convenio->getCodigo() : $convenio;
        return $this;
    }

    protected function normalizeParameter_FecIni()
    {
        if (!$this->parameter_FecIni) {
            return $this->utils->dateFormatToday('m/d/Y');
        } else {
            return $this->parameter_FecIni->format('m/d/Y');
        }
    }

    protected function normalizeParameter_FecFin()
    {
        if (!$this->parameter_FecFin) {
            return $this->utils->dateFormatToday('m/d/Y');
        } else {
            return $this->parameter_FecFin->format('m/d/Y');
        }
    }

    public function setTipoLiquidacionNomina()
    {
        $this->parameter_TipLiq = '01';
    }

    public function setTipoLiquidacionPrima()
    {
        $this->parameter_TipLiq = '02';
    }

    public function setTipoLiquidacionContrato()
    {
        $this->parameter_TipLiq = '04';
    }

    public function setIdentificacion($identificacion)
    {
        $this->parameter_CodEmp = $identificacion;
    }

    public function getPdfFileName(): string
    {
        $fecha = $this->parameter_FecIni->format('Ymd') . '-' . $this->parameter_FecFin->format('Ymd');
        return 'novasoft/clientes/listado-nomina/' . $this->parameter_cod_conv . '-' . $fecha . '.pdf';
    }
}