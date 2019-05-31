<?php


namespace App\Service\NovasoftSsrs\Report;


use App\Entity\ReporteNomina;
use App\Repository\UsuarioRepository;
use App\Service\NovasoftSsrs\Mapper\MapperNom204;
use App\Service\NovasoftSsrs\ReportFormatter;
use App\Service\NovasoftSsrs\ReportServer;
use App\Service\Utils;

class ReportNom204 extends Report
{

    /**
     * @var MapperNom204
     */
    protected $mapperClass;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    protected function getReportPath(): string
    {
        return "/ReportesWeb/NOM/NOM204";
    }

    protected function getMapperClass(): ?string
    {
        return MapperNom204::class;
    }

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


    public function __construct(ReportServer $reportServer, ReportFormatter $reportFormatter, Utils $utils,
                                $novasoftSsrsDb, UsuarioRepository $usuarioRepository)
    {
        parent::__construct($reportServer, $reportFormatter, $utils, $novasoftSsrsDb);

        $this->parameter_FecIni = '2/1/2017';
        $this->parameter_FecFin = $this->utils->dateFormatToday('m/d/Y');
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * @param \DateTime $fechaInicio
     * @return $this
     */
    public function setParameterFechaInicio($fechaInicio)
    {
        $this->parameter_FecIni = $fechaInicio->format('m/d/Y');
        return $this;
    }

    /**
     * @param \DateTime $fechaFin
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

    /**
     * @return ReporteNomina[]
     * @throws \SSRS\SSRSReportException
     */
    public function renderMap()
    {
        return $this->reportFormatter->mapCsv($this->renderCSV(), $this->getMapperInstance());
    }

    protected function getMapperInstance()
    {
        return new $this->mapperClass($this->usuarioRepository);
    }
}