<?php


namespace App\Service;


use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\ReporteNomina;
use App\Service\Configuracion\SsrsDb;
use App\Service\NovasoftSsrs\Entity\ReporteCertificadoIngresos;
use App\Service\NovasoftSsrs\Entity\ReporteCertificadoLaboral;
use App\Service\NovasoftSsrs\Entity\ReporteLiquidacion;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use App\Service\NovasoftSsrs\Report\Report;

class ReportesServicioEmpleados
{
    /**
     * @var NovasoftSsrs
     */
    private $novasoftSsrs;

    public function __construct(NovasoftSsrs $novasoftSsrs)
    {
        $this->novasoftSsrs = $novasoftSsrs;
    }

    /**
     * @param $empleadoIdent
     * @return ReporteNomina[]|null
     * @deprecated
     */
    public function getComprobantesDePago($empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom204();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);

        $nominas = $reporteNovasoft->renderMap();

        krsort($nominas);

        return $nominas;
    }

    /**
     * @param string|\DateTimeInterface $fecha
     * @param $empleadoIdent
     * @return mixed
     * @deprecated
     */
    public function getComprobanteDePagoPdf($fecha, $empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom204();

        if(is_string($fecha)) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $fecha);
        }

        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent)
            ->setParameterFechaInicio($fecha)
            ->setParameterFechaFin($fecha);

        return $reporteNovasoft->renderPdf();
    }

    /**
     * @param $empleadoIdent
     * @return ReporteCertificadoLaboral[]|null
     */
    public function getCertificadosLaborales($empleadoIdent)
    {
        $reporte = $this->getCertificadoLaboral($empleadoIdent);
        return $reporte ? [$reporte] : [];
    }

    /**
     * @param $empleadoIdent
     * @return ReporteCertificadoLaboral|null
     */
    public function getCertificadoLaboral($empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom932();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);
        return $reporteNovasoft->renderCertificado();
    }

    /**
     * @param $empleadoIdent
     * @return ReporteCertificadoIngresos[]
     * @deprecated
     */
    public function getCertificadosIngresos($empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom92117();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);

        $certificados = [];
        $anos = ['2018', '2017'];
        foreach ($anos as $ano) {
            $reporteNovasoft->setParameterAno($ano);
            $certificados[$ano] = $reporteNovasoft->renderCertificado();
        }

        return $certificados;
    }

    /**
     * @param $periodo
     * @param $empleadoIdent
     * @return mixed
     * @deprecated
     */
    public function getCertificadoIngresosPdf($periodo, $empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom92117();
        $reporteNovasoft
            ->setParameterCodigoEmpleado($empleadoIdent)
            ->setParameterAno($periodo);
        return $reporteNovasoft->renderPdf();
    }

    /**
     * @param string $empleadoIdent
     * @return ReporteLiquidacion[]
     */
    public function getLiquidacionesDeContrato(string $empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom701();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);
        return $reporteNovasoft->renderMap();
    }

    public function getLiquidacionDeContratoPdf(string $empleadoIdent, $fechaIngreso, $fechaRetiro)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom701();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);
        return $reporteNovasoft->renderPdf();
    }

    /**
     * @return Convenio[]
     * @throws \SSRS\SSRSReportException
     */
    public function getConvenios()
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom936();
        return $reporteNovasoft->renderMap();
    }

    /**
     * @param string $convenioCodigo
     * @param \DateTime $desde
     * @param \DateTime $hasta
     * @return Empleado[]
     */
    public function getEmpleados(string $convenioCodigo, $desde, $hasta)
    {
        $reportNovasoft = $this->novasoftSsrs->getReportNomU1503();
        $reportNovasoft->setParameterCodigoConvenio($convenioCodigo);
        $reportNovasoft->setParameterFechaDesde($desde);
        $reportNovasoft->setParameterFechaHasta($hasta);

        return $reportNovasoft->renderMap();
    }

    public function getEmpleado($ident = null)
    {
        $reportNovasoft = $this->novasoftSsrs->getReportNom933();
        if($ident) {
            $reportNovasoft->setParameterCodigoEmpleado($ident);
        }
        return $reportNovasoft->renderMap();
    }

    public function setSsrsDb(string $ssrsDb): ReportesServicioEmpleados
    {
        $this->novasoftSsrs->setSsrsDb($ssrsDb);
        return $this;
    }

}