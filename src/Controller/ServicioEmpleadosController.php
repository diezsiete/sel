<?php

namespace App\Controller;


use App\DataTable\Type\ReporteNominaDataTableType;
use App\Entity\ReporteNomina;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\ReportesServicioEmpleados;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServicioEmpleadosController extends BaseController
{
    /**
     * @Route("/comprobantes", name="app_comprobantes")
     */
    public function comprobantes(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(ReporteNominaDataTableType::class,
            ['identificacion' => '1010187047'], ['searching' => false, 'paging' => false])
            ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('servicio_empleados/comprobantes.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/comprobante/{comprobante}", name="app_comprobante")
     */
    public function comprobante(ReportesServicioEmpleados $reportes, ReporteNomina $comprobante)
    {
        $comprobantePdf = $reportes
            ->getComprobanteDePagoPdf($comprobante->getFecha(), $comprobante->getUsuario()->getIdentificacion());
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

    /**
     * @Route("/liquidaciones-de-contrato", name="app_liquidaciones_de_contrato")
     */
    public function liquidacionesDeContrato(ReportesServicioEmpleados $reportes)
    {
        $liquidaciones = $reportes->getLiquidacionesDeContrato('1023010041');
        return $this->render('servicio_empleados/liquidaciones-de-contrato.html.twig', [
            'liquidaciones' => $liquidaciones
        ]);
    }

    /**
     * @Route("/liquidacion-de-contrato/{fechaIngreso}/{fechaRetiro}", name="app_liquidacion_de_contrato_pdf")
     */
    public function liquidacionDeContratoPdf(ReportesServicioEmpleados $reportes, $fechaIngreso, $fechaRetiro)
    {
        $reportePdf = $reportes->getLiquidacionDeContratoPdf('1023010041', $fechaIngreso, $fechaRetiro);
        return $this->renderPdf($reportePdf);
    }
}
