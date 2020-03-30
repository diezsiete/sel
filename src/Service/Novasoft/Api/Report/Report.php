<?php


namespace App\Service\Novasoft\Api\Report;

use App\Service\ServicioEmpleados\Report\PdfHandler;

abstract class Report
{
    public function __construct(PdfHandler $pdfHandler)
    {
        $this->pdfHandler = $pdfHandler;
    }

    public function streamPdf($report)
    {
        // usar cacheAndStream si se quiere cache
        return $this->pdfHandler->writeAndStream($this->getPdfFileName($report), function () use($report) {
            return $this->renderPdf($report);
        });

    }

    abstract function getPdfFileName($report): string;
}