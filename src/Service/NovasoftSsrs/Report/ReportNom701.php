<?php


namespace App\Service\NovasoftSsrs\Report;


use App\Service\NovasoftSsrs\Mapper\MapperNom701;
use App\Service\NovasoftSsrs\ReportFormatter;
use App\Service\NovasoftSsrs\ReportServer;
use App\Service\Utils;

class ReportNom701 extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM701";

    protected $mapperClass = MapperNom701::class;

    protected $parameter_Ind_Fec = "1";
    protected $parameter_fFecIni = "3/1/2017";
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

    public function __construct(ReportServer $reportServer, ReportFormatter $reportFormatter, Utils $utils, $novasoftSsrsDb)
    {
        parent::__construct($reportServer, $reportFormatter, $utils, $novasoftSsrsDb);

        $this->parameter_fFecIni = '2/1/2017';
        $this->parameter_fFecFin = $this->utils->dateFormatToday('m/t/Y');
    }

    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_CodEmp = $identificacion;
        return $this;
    }

    public function setParameterFechaInicio(\DateTime $fechaInicio)
    {
        $this->parameter_fFecIni = $fechaInicio->format('m/d/Y');
        return $this;
    }

    public function setParameterFechaFin(\DateTime $fechaFin)
    {
        $this->parameter_fFecFin = $fechaFin->format('m/d/Y');
        return $this;
    }
}