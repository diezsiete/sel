<?php


namespace App\Service\Napi\Report;

use App\Entity\Napi\ServicioEmpleados\CertificadoIngresos;
use App\Service\Pdf\ServicioEmpleados\Report\CertificadoIngresosPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class CertificadoIngresosReport extends Report
{
    /**
     * @var CertificadoIngresosPdf
     */
    private $pdfService;
    /**
     * @var CertificadoIngresos
     */
    private $currentCertificado;

    public function __construct(PdfHandler $pdfHandler, CertificadoIngresosPdf $pdfService)
    {
        parent::__construct($pdfHandler);
        $this->pdfService = $pdfService;
    }

    public function setCertificado(CertificadoIngresos $certificado): self
    {
        $this->currentCertificado = $certificado;
        return $this;
    }

    public function renderPdf()
    {
        return $this->pdfService->build($this->currentCertificado)->Output('S');
    }

    /**
     * @return string
     */
    public function getPdfFileName(): string
    {
        $ano = $this->currentCertificado->getFechaInicial()->format('Y');
        return 'napi/se/certificado-ingresos/' . $this->currentCertificado->getIdentificacion(). '-'.$ano.'.pdf';
    }
}