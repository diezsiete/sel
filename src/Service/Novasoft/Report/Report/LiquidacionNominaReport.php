<?php

namespace App\Service\Novasoft\Report\Report;

use App\Entity\Main\Convenio;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\LiquidacionNominaImporter;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\LiquidacionNominaMapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\Utils;
use DateTimeInterface;
use SSRS\SSRSReport;

class LiquidacionNominaReport extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM1529";
    // sp_rhh_RepNom211
        // select * from sis_aplicacion where cod_apl ='nom'
        // rs_gen_empresa
        // rs_gen_ccosto
        // rs_gen_Sucur
        // rs_gen_cl1
        // rs_gen_cl2
        // rs_gen_cl3
        // rs_gen_Cia
        // SELECT R.Cod_SGC from web_reportes R where R.cod_apl='NOM' AND cod_rep = 'NOM1529'
        // rs_rhh_tipliq
        // rs_rhh_Convenio_Usuario

    /**
     * Filtrar por:
     *     Fecha Liquidación: 0
     *     Fecha Corte: 1
     */
    protected $parameter_IndFec = 0;

    /**
     * Fecha Inicial:
     * @var DateTimeInterface
     */
    protected $parameter_fFecIni;

    /**
     * Fecha Final
     * @var DateTimeInterface
     */
    protected $parameter_fFecFin;

    /**
     * @var string
     */
    protected $parameter_CodConv;

    /**
     * Compañía
     * puede ser '%' o
     * "%": <TODOS>
     * "0  ": NO APLICA
     * "01 ": PTA-SAS
     * @var string
     */
    protected $parameter_cod_cia = "01 ";

    /**
     * Sucursal
     * puede ser '%' o
     * "%": <TODOS>
     * "0  ": NO APLICA
     * "BAR": BARRANQUILLA
     * "BOG": BOGOTA
     * ....
     * @var
     */
    protected $parameter_CodSuc = "%";

    /**
     * Centro Costo
     *
     * "%": <TODOS>
     * "0         ": NO APLICA
     * 3... 1130
     * @var string|int
     */
    protected $parameter_CodCco = "%";

    /**
     * Clasificador 1: (Es un convenio pero no se para que)
     * puede ser '%'
     * @var
     */
    protected $parameter_cod_cla1 = '%';

    /**
     * Clasificador 2
     * @var string
     */
    protected $parameter_cod_cla2 = '%';

    /**
     * Empleado ident
     * @var string
     */
    protected $parameter_CodEmp = '%';

    /**
     * Tipo Liquidación
     * "%": TODAS (restringida)
     * "01": Liquidación de Nómina
     * "02": Prima de Servicios
     * "04": Liquidacion de Contrato
     * @var string
     */
    protected $parameter_TipLiq = '01';

    /**
     * Origen Datos
     * "P": PRENOMINA
     * "H": HISTORICO
     * @var string
     */
    protected $parameter_Origen = 'H';

    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, LiquidacionNominaMapper $mapper, LiquidacionNominaImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
    }

    protected function normalizeParameter_fFecIni()
    {
        if(!$this->parameter_fFecIni) {
            return $this->utils->dateFormatToday('m/d/Y');
        } else {
            return $this->parameter_fFecIni->format('m/d/Y');
        }
    }

    protected function normalizeParameter_fFecFin()
    {
        if(!$this->parameter_fFecFin) {
            return $this->utils->dateFormatToday('m/d/Y');
        } else {
            return $this->parameter_fFecFin->format('m/d/Y');
        }
    }

    public function setFechaInicial(DateTimeInterface $fecha)
    {
        $this->parameter_fFecIni = $fecha;
        return $this;
    }

    public function setFechaFinal(DateTimeInterface $fecha)
    {
        $this->parameter_fFecFin = $fecha;
        return $this;
    }

    /**
     * @param Convenio|string $convenio
     * @return LiquidacionNominaReport
     */
    public function setConvenio($convenio)
    {
        $this->parameter_CodConv = is_object($convenio) ? $convenio->getCodigo() : $convenio;
        return $this;
    }

    public function setIdentificacion($identificacion)
    {
        $this->parameter_CodEmp = $identificacion;
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

    public function getPdfFileName(): string
    {
        $fecha = $this->parameter_fFecIni->format('Ymd') . '-' . $this->parameter_fFecFin->format('Ymd');
        return 'novasoft/liquidacion-nomina' . '/' . $this->parameter_CodConv . '-' . $fecha . '.pdf';
    }
}