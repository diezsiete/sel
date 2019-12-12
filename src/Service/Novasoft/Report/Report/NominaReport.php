<?php


namespace App\Service\Novasoft\Report\Report;


use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Mapper\MapperNom204;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\Utils;
use DateTime;
use SSRS\SSRSReport;

class NominaReport extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM204";

    /**
     * Fecha de corte
     * @var int
     */
    protected $parameter_Ind_Fec = 1;
    /**
     * Fecha desde
     * Si no se asigna, toma la fecha actual
     * @var string
     */
    protected $parameter_FecIni;
    /**
     * Fecha hasta
     * Si no se asigna, toma la fecha actual
     * @var string
     */
    protected $parameter_FecFin;
    /**
     * Codigo convenio
     * @var string
     */
    protected $parameter_CodConv = "%";
    /**
     * @var string
     */
    protected $parameter_cod_cia = "%";
    /**
     * @var string
     */
    protected $parameter_CodSuc = "%";
    /**
     * @var string
     */
    protected $parameter_CodCco = "%";
    /**
     * @var string
     */
    protected $parameter_cod_cla1 = "%";
    /**
     * @var string
     */
    protected $parameter_cod_cla2 = "%";
    /**
     * @var string
     */
    protected $parameter_cod_cla3 = "%";
    /**
     * @var string
     */
    protected $parameter_CodEmp = "19213367";
    /**
     * Liquidación de Nomina [01]
     * @var string
     */
    protected $parameter_TipLiq = "%";
    /**
     * Historico
     * @var string
     */
    protected $parameter_Origen = "H";


    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, MapperNom204 $mapper)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper);

        $this->parameter_FecIni = '2/1/2017';
        $this->parameter_FecFin = $utils->dateFormatToday('m/d/Y');
    }


    /**
     * @param DateTime $fechaInicio
     * @return $this
     */
    public function setParameterFechaInicio($fechaInicio)
    {
        $this->parameter_FecIni = $fechaInicio->format('m/d/Y');
        return $this;
    }

    /**
     * @param DateTime $fechaFin
     * @return $this
     */
    public function setParameterFechaFin($fechaFin)
    {
        $this->parameter_FecFin = $fechaFin->format('m/d/Y');
        return $this;
    }

    /**
     * @param $identificacion
     * @return $this
     */
    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_CodEmp = $identificacion;
        return $this;
    }


    public function renderMap()
    {
        $map = parent::renderMap();
        krsort($map);
        return $map;
    }

}