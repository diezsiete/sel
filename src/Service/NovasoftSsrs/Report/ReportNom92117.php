<?php
/**
 * Reporte datos certificado laboral
 */

namespace App\Service\NovasoftSsrs\Report;


use App\Service\NovasoftSsrs\Entity\ReporteCertificadoIngresos;
use App\Service\NovasoftSsrs\Entity\ReporteCertificadoLaboral;
use App\Service\NovasoftSsrs\Mapper\MapperNom92117;
use App\Service\NovasoftSsrs\Mapper\MapperNom932;
use App\Service\NovasoftSsrs\ReportFormatter;
use App\Service\NovasoftSsrs\ReportServer;
use App\Service\Utils;

/**
 * Class ReportNom92117
 * @package App\Service\NovasoftSsrs\Report
 * @deprecated
 */
class ReportNom92117 extends Report
{
    protected function getReportPath(): string
    {
        return "/ReportesWeb/NOM/NOM921_17";
    }

    protected function getMapperClass(): ?string
    {
        return MapperNom92117::class;
    }

    /**
     * @var string
     */
    protected $parameter_Fec_ini = '1/1/2017';
    /**
     * @var string
     */
    protected $parameter_Fec_fin = '12/31/2017';
    /**
     * @var string
     */
    protected $parameter_Cod_Conv = "%";
    /**
     * @var string
     */
    protected $parameter_cod_cia = "%";
    /**
     * @var string
     */
    protected $parameter_cod_suc = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cco = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla1 = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla2 = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla3 = "%";
    /**
     * @var
     */
    protected $parameter_fec_exp;
    /**
     * @var string
     */
    protected $parameter_Ccod_emp = "%";

    public function __construct(ReportServer $reportServer, ReportFormatter $reportFormatter, Utils $utils)
    {
        parent::__construct($reportServer, $reportFormatter, $utils);

        $this->parameter_fec_exp = (new \DateTime())->format('m/d/Y');
    }

    /**
     * @param string $ano
     * @return ReportNom92117
     */
    public function setParameterAno(string $ano)
    {
        $this->parameter_Fec_ini = str_replace("2017", $ano, $this->parameter_Fec_ini);
        $this->parameter_Fec_fin = str_replace("2017", $ano, $this->parameter_Fec_fin);
        return $this;
    }

    /**
     * @param $identificacion
     * @return $this
     */
    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_Ccod_emp = $identificacion;
        return $this;
    }

    public function renderMap()
    {
        $csvAssociative = $this->reportFormatter->csvContSplittedToAssociative($this->renderCSV());
        return $this->reportFormatter->mapCsv($csvAssociative, $this->getMapperInstance());
    }

    /**
     * @return ReporteCertificadoIngresos|null
     * @throws \SSRS\SSRSReportException
     */
    public function renderCertificado()
    {
        $certificadoData = $this->renderMap();
        return $certificadoData ? $certificadoData[0] : null;
    }
}