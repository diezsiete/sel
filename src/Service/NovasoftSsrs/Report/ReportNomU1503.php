<?php
/**
 * Reporte novasoft que trae todos los empleados
 * User: guerrerojosedario
 * Date: 2018/08/14
 * Time: 10:42 PM
 */

namespace App\Service\NovasoftSsrs\Report;

use App\Entity\Main\Empleado;
use App\Repository\Main\ConvenioRepository;
use App\Service\NovasoftSsrs\Mapper\MapperNomU1503;
use App\Service\NovasoftSsrs\ReportFormatter;
use App\Service\NovasoftSsrs\ReportServer;
use App\Service\Utils;

class ReportNomU1503 extends Report
{
    /**
     * @var \App\Repository\Main\ConvenioRepository
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
                                ConvenioRepository $convenioRepository)
    {
        parent::__construct($reportServer, $reportFormatter, $utils);

        $today = $this->utils->dateFormatToday('m/d/Y');
        $this->parameter_feccori = $today;
        $this->parameter_feccorf = $today;
        $this->convenioRepository = $convenioRepository;
    }

    /**
     * @param \DateTime $fechaDesde
     * @return ReportNomU1503
     */
    public function setParameterFechaDesde($fechaDesde)
    {
        $this->parameter_feccori = $fechaDesde->format('m/d/Y');
        return $this;
    }

    /**
     * @param \DateTime $fechaHasta
     * @return ReportNomU1503
     */
    public function setParameterFechaHasta($fechaHasta)
    {
        $this->parameter_feccorf = $fechaHasta->format('m/d/Y');
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

    protected function getMapperInstance()
    {
        return new $this->mapperClass($this->convenioRepository);
    }
}