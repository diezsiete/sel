<?php

namespace App\Controller;


use App\Service\NovasoftSsrs\Entity\ReporteCertificadoIngresos;
use App\Service\NovasoftSsrs\Report\ReportNom204;
use App\Service\NovasoftSsrs\Report\ReportNom92117;
use App\Service\NovasoftSsrs\Report\ReportNom932;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\ReportesServicioEmpleados;
use Symfony\Component\Routing\Annotation\Route;

class ServicioEmpleadosController extends BaseController
{
    /**
     * @Route("/comprobantes", name="app_comprobantes")
     */
    public function comprobantes(ReportesServicioEmpleados $reportes)
    {
        $comprobantes = $reportes->getComprobantesDePago('53124855');
        return $this->render('servicio_empleados/comprobantes.html.twig', [
            'comprobantes' => $comprobantes
        ]);
    }

    /**
     * @Route("/comprobante/{comprobanteId}", name="app_comprobante")
     */
    public function comprobante(ReportesServicioEmpleados $reportes, $comprobanteId)
    {
        $comprobantePdf = $reportes->getComprobanteDePagoPdf($comprobanteId, '53124855');
        return $this->renderPdf($comprobantePdf);
    }

    /**
     * @Route("/certificado-laboral", name="app_certificado_laboral")
     */
    public function certificadoLaboral(ReportesServicioEmpleados $reportes)
    {
        $certificados = $reportes->getCertificadosLaborales('53124855');
        return $this->render('servicio_empleados/certificado-laboral.html.twig', [
            'tieneCertificado' => count($certificados) > 0,
        ]);
    }

    /**
     * @Route("/certificado-laboral-pdf", name="app_certificado_laboral_pdf")
     */
    public function certificadoLaboralPdf(ReportesServicioEmpleados $reportes, PdfCartaLaboral $pdf)
    {
        $certificado = $reportes->getCertificadoLaboral('53124855');
        //TODO no hay certificado
        $pdfContent = $this->render('servicio_empleados/certificado-laboral.pdf.php', [
            'pdf' => $pdf,
            'certificado' => $certificado,
        ]);
        return $this->renderPdf($pdfContent);
    }

    /**
     * @Route("/certificados-ingresos", name="app_certificados_ingresos")
     */
    public function certificadosIngresos(ReportesServicioEmpleados $reportes)
    {
        $certificados = $reportes->getCertificadosIngresos('53124855');
        return $this->render('servicio_empleados/certificado-ingresos.html.twig', [
            'certificados' => $certificados
        ]);
    }

    /**
     * @Route("/certificado-ingresos/{periodo}", name="app_certificado_ingresos")
     */
    public function certificadoIngreso(ReportesServicioEmpleados $reportes, $periodo)
    {
        $reportePdf = $reportes->getCertificadoIngresosPdf($periodo, '53124855');
        return $this->renderPdf($reportePdf);
    }
    
}
