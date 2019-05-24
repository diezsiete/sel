<?php
/**
 * Reporte novasoft que trae todos los empleados
 * User: guerrerojosedario
 * Date: 2018/08/14
 * Time: 10:42 PM
 */

namespace App\Service\NovasoftSsrs\Report;

use App\Service\NovasoftSsrs\ReportFormatter;
use App\Service\NovasoftSsrs\ReportServer;
use App\Service\Utils;

class ReportNomU1503 extends Report
{
    protected $path = "/ReportesWeb/NOM/NOMU1503";

    /**
     * Fecha desde
     * Si no se asigna, toma la fecha actual
     * @var string
     */
    protected $parameter_feccori;

    /**
     * Fecha hasta
     * Si no se asigna, toma la fecha actual
     * @var string
     */
    protected $parameter_feccorf;

    /**
     * Codigo convenio
     * @var string
     */
    protected $parameter_codconv = "%";

    /**
     * valid values:
     *  1 : TODOS
     *  2 : INGRESOS
     *  3 : RETIROS
     * @var int
     */
    protected $parameter_estado = 1;




    public function __construct(ReportServer $reportServer, ReportFormatter $reportFormatter, Utils $utils, $novasoftSsrsDb)
    {
        parent::__construct($reportServer, $reportFormatter, $utils, $novasoftSsrsDb);

        $today = $this->utils->dateFormatToday('m/d/Y');
        $this->parameter_feccori = $today;
        $this->parameter_feccorf = $today;
    }

    /**
     * @param string $parameter_feccori
     * @return ReportNomU1503
     */
    public function setParameterFechaDesde($parameter_feccori)
    {
        $this->parameter_feccori = $parameter_feccori;
        return $this;
    }

    /**
     * @param string $parameter_feccorf
     * @return ReportNomU1503
     */
    public function setParameterFechaHasta($parameter_feccorf)
    {
        $this->parameter_feccorf = $parameter_feccorf;
        return $this;
    }

    /**
     * @param string $parameter_codconv
     * @return ReportNomU1503
     */
    public function setParameterCodigoConvenio($parameter_codconv)
    {
        $this->parameter_codconv = $parameter_codconv;
        return $this;
    }

    /**
     * @param int $parameter_estado
     * @return ReportNomU1503
     */
    public function setParameterEstado($parameter_estado)
    {
        $this->parameter_estado = $parameter_estado;
        return $this;
    }

    /**
     * @return NovasoftEmpleado[]
     */
    public function renderMap()
    {
        return parent::renderMap();
    }
}