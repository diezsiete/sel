<?php

namespace App\Service\Napi\Report;

use App\Service\ServicioEmpleados\Report\PdfHandler;

/**
 * Utilizado por los reportes que generan el pdf localmente basado en info retornada por napi
 * Trait LocalPdf
 * @package App\Service\Napi\Report
 */
trait LocalPdf
{
    /**
     * @var PdfHandler
     */
    protected $pdfHandler;

    /**
     * @param PdfHandler $pdfHandler
     * @required
     */
    public function setPdfHandler(PdfHandler $pdfHandler): void
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

    public function linkPdf(): string
    {
        return $this->pdfHandler->writeAndLink($this->getPdfFileName(), function () {
            return $this->renderPdf();
        });
    }

    abstract public function getPdfFileName(): string;
    abstract public function renderPdf();
}