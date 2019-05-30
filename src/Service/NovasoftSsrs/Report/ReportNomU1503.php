<?php
/**
 * Reporte novasoft que trae todos los empleados
 * User: guerrerojosedario
 * Date: 2018/08/14
 * Time: 10:42 PM
 */

namespace App\Service\NovasoftSsrs\Report;

use App\Entity\Empleado;
use App\Repository\ConvenioRepository;
use App\Service\NovasoftSsrs\Mapper\MapperNomU1503;
use App\Service\NovasoftSsrs\ReportFormatter;
use App\Service\NovasoftSsrs\ReportServer;
use App\Service\Utils;

class ReportNomU1503 extends Report
{
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;

    protected function getReportPath(): string
    {
        return "/ReportesWeb/NOM/NOMU1503";
    }

    protected function getMapperClass(): ?string
    {
        return MapperNomU1503::class;
    }

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



    public function __construct(ReportServer $reportServer, ReportFormatter $reportFormatter, Utils $utils,
                                $novasoftSsrsDb, ConvenioRepository $convenioRepository)
    {
        parent::__construct($reportServer, $reportFormatter, $utils, $novasoftSsrsDb);

        $today = $this->utils->dateFormatToday('m/d/Y');
        $this->parameter_feccori = $today;
        $this->parameter_feccorf = $today;
        $this->convenioRepository = $convenioRepository;
    }

    /**
     * @param string|\DateTime|null $fechaDesde
     * @return ReportNomU1503
     */
    public function setParameterFechaDesde($fechaDesde)
    {
        if($fechaDesde) {
            $this->parameter_feccori = is_object($fechaDesde) ? $fechaDesde->format('m/d/Y') : $fechaDesde;
        }
        return $this;
    }

    /**
     * @param string|\DateTime|null $fechaHasta
     * @return ReportNomU1503
     */
    public function setParameterFechaHasta($fechaHasta)
    {
        if($fechaHasta) {
            $this->parameter_feccorf = is_object($fechaHasta) ? $fechaHasta->format('m/d/Y') : $fechaHasta;
        }
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
     * @return Empleado[]
     */
    public function renderMap()
    {
        return parent::renderMap();
    }

    protected function getMapperInstance()
    {
        return new $this->mapperClass($this->convenioRepository);
    }
}