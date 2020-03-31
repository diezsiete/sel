<?php


namespace App\Service\Napi\Report;

use App\Service\ServicioEmpleados\Report\PdfHandler;

abstract class Report
{
    public function __construct(PdfHandler $pdfHandler)
    {
        $this->pdfHandler = $pdfHandler;
    }

    public function streamPdf()
    {
        // usar writeAndStream si no se quiere cache
        return $this->pdfHandler->cacheAndStream($this->getPdfFileName(), function () {
            return $this->renderPdf();
        });

    }

    public function linkPdf()
    {
        return $this->pdfHandler->cacheAndLink($this->getPdfFileName(), function () {
            return $this->renderPdf();
        });
    }

    abstract function getPdfFileName(): string;
    abstract public function renderPdf();
}