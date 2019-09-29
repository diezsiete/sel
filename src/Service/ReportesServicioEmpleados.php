<?php


namespace App\Service;


use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\ReporteNomina;
use App\Service\Configuracion\Configuracion;
use App\Service\Configuracion\SsrsDb;
use App\Service\NovasoftSsrs\Entity\ReporteCertificadoIngresos;
use App\Service\NovasoftSsrs\Entity\ReporteCertificadoLaboral;
use App\Service\NovasoftSsrs\Entity\ReporteLiquidacion;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use App\Service\NovasoftSsrs\Report\Report;
use App\Service\NovasoftSsrs\Report\ReportNom933;
use App\Service\NovasoftSsrs\Report\ReportNomU1503;
use DateTime;
use Exception;
use SSRS\SSRSReportException;

class ReportesServicioEmpleados
{
    /**
     * @var NovasoftSsrs
     */
    private $novasoftSsrs;
    /**
     * @var SsrsDb
     */
    private $ssrsDb;
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(NovasoftSsrs $novasoftSsrs, Configuracion $configuracion)
    {
        $this->novasoftSsrs = $novasoftSsrs;
        $this->configuracion = $configuracion;
        $this->ssrsDb = $configuracion->getSsrsDb(true);
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
            $fecha = DateTime::createFromFormat('Y-m-d', $fecha);
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
    public function getCertificadosLaborales($empleadoIdent, $ssrsDb)
    {
        $reporte = $this->getCertificadoLaboral($empleadoIdent, $ssrsDb);
        return $reporte ? [$reporte] : [];
    }

    /**
     * @param $empleadoIdent
     * @return ReporteCertificadoLaboral|null
     */
    public function getCertificadoLaboral($empleadoIdent, $ssrsDb)
    {
        $reporteNovasoft = $this->novasoftSsrs->setSsrsDb($ssrsDb)->getReportNom932();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);
        return $reporteNovasoft->renderCertificado();
    }

    /**
     * @param $empleadoIdent
     * @return ReporteCertificadoIngresos[]
     * @deprecated
     */
    public function getCertificadosIngresos($empleadoIdent, $ssrsDb)
    {
        $reporteNovasoft = $this->novasoftSsrs->setSsrsDb($ssrsDb)->getReportNom92117();
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
     * @throws SSRSReportException
     */
    public function getConvenios()
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom936();
        return $reporteNovasoft->renderMap();
    }

    /**
     * @param string $convenioCodigo
     * @param DateTime $desde
     * @param DateTime $hasta
     * @return Empleado[]
     * @throws SSRSReportException
     * @throws Exception
     */
    public function getEmpleados(string $convenioCodigo, $desde = null, $hasta = null)
    {
        /** @var ReportNomU1503|ReportNom933 $reportNovasoft */
        $reportNovasoft = $this->novasoftSsrs->getReport($this->ssrsDb->getReporteEmpleado());

        if($reportNovasoft instanceof ReportNomU1503) {
            if(!$convenioCodigo || !$desde || !$hasta) {
                throw new Exception("Parametros incompletos");
            }
            $reportNovasoft->setParameterCodigoConvenio($convenioCodigo);
            $reportNovasoft->setParameterFechaDesde($desde);
            $reportNovasoft->setParameterFechaHasta($hasta);
        }

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
        $this->ssrsDb = $this->configuracion->getSsrsDb($ssrsDb);
        $this->novasoftSsrs->setSsrsDb($ssrsDb);
        return $this;
    }

}