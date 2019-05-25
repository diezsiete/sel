<?php


namespace App\Service;


use App\Service\NovasoftSsrs\Entity\ReporteCertificadoIngresos;
use App\Service\NovasoftSsrs\Entity\ReporteCertificadoLaboral;
use App\Service\NovasoftSsrs\Entity\ReporteLiquidacion;
use App\Service\NovasoftSsrs\Entity\ReporteNomina;
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
     * @param $comprobanteId
     * @param $empleadoIdent
     * @return mixed
     */
    public function getComprobanteDePagoPdf($comprobanteId, $empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom204();

        $fecha = \DateTime::createFromFormat('Y-m-d', $comprobanteId);

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
     */
    public function getCertificadosIngresos($empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom92117();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);

        $certificados = [];
        $anos = ['2018', '2017'];
        foreach($anos as $ano) {
            $reporteNovasoft->setParameterAno($ano);
            $certificados[$ano] = $reporteNovasoft->renderCertificado();
        }

        return $certificados;
    }

    /**
     * @param $periodo
     * @param $empleadoIdent
     * @return mixed
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
}