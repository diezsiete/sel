<?php

namespace App\Controller;


use App\Service\NovasoftSsrs\Report\ReportNom204;
use App\Service\NovasoftSsrs\Report\ReportNom932;
use Symfony\Component\Routing\Annotation\Route;

class ServicioEmpleadosController extends BaseController
{
    /**
     * @Route("/comprobantes", name="app_comprobantes")
     */
    public function comprobantes(ReportNom204 $reporte)
    {
        $reporte->setParameterCodigoEmpleado('53124855');
        $nominas = $reporte->renderMap();

        krsort($nominas);

        return $this->render('servicio_empleados/comprobantes.html.twig', [
            'nominas' => $nominas
        ]);
    }

    /**
     * @Route("/comprobante/{fecha}", name="app_comprobante")
     */
    public function comprobante(ReportNom204 $reporte, $fecha)
    {
        $fecha = \DateTime::createFromFormat('Y-m-d', $fecha);

        $reporte->setParameterCodigoEmpleado('53124855')
            ->setParameterFechaInicio($fecha)
            ->setParameterFechaFin($fecha);

        return $this->renderPdf($reporte->renderPdf());
    }

    /**
     * @Route("/certificado-laboral", name="app_certificado_laboral")
     */
    public function certificadoLaboral(ReportNom932 $reporte)
    {
        $reporte->setParameterCodigoEmpleado('53124855');
        // $x = $reporte->renderMap();
        return $this->render('servicio_empleados/certificado-laboral.html.twig', [
            'tieneCertificado' => true,
        ]);
    }

    /**
     * @Route("/certificado-laboral-pdf", name="app_certificado_laboral_pdf")
     */
    public function certificadoLaboralPdf(ReportNom932 $reporte)
    {
        $reporte->setParameterCodigoEmpleado('53124855');
        $pdfData = $reporte->renderMap();
    }

    /**
     * @Route("/certificado-ingresos", name="app_certificado_ingresos")
     */
    public function certificadoIngresos()
    {

    }
    
}
