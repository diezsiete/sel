<?php


namespace App\Service\Novasoft\Api\Report\ServicioEmpleados;


use App\Entity\Novasoft\Report\ServicioEmpleados\CertificadoIngresos;
use App\Service\Novasoft\Api\Report\Report;
use App\Service\Pdf\ServicioEmpleados\Report\CertificadoIngresosPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class CertificadoIngresosReport extends Report
{
    /**
     * @var CertificadoIngresosPdf
     */
    private $pdfService;

    public function __construct(PdfHandler $pdfHandler, CertificadoIngresosPdf $pdfService)
    {
        parent::__construct($pdfHandler);
        $this->pdfService = $pdfService;
    }

    public function renderPdf(CertificadoIngresos $certificado)
    {
//        $certificado = $this->certificadoIngresosRepo->findCertificado(
//            $this->usuario->getIdentificacion(), $this->empresaUsuario, $this->noContrat, $this->ano
//        );


        return $this->pdfService->build($certificado)->Output('S');
    }

    /**
     * @param CertificadoIngresos $certificado
     * @return string
     */
    public function getPdfFileName($certificado): string
    {
        $ano = $certificado->getFechaInicial()->format('Y');
        return 'novasoft/api/report/se/certificado-ingresos/' . $certificado->getIdentificacion(). '-'.$ano.'.pdf';
    }
}