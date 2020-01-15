<?php


namespace App\Service\Halcon\Report\Report;


use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\ServicioEmpleados\Report\ReportInterface;

abstract class Report implements ReportInterface
{
    /**
     * @var PdfHandler
     */
    protected $pdfHandler;

    public function __construct(PdfHandler $pdfHandler)
    {
        $this->pdfHandler = $pdfHandler;
    }

    public function streamPdf()
    {
        // usar cacheAndStream si se quiere cache
        return $this->pdfHandler->writeAndStream($this->getPdfFileName(), function () {
            return $this->renderPdf();
        });

    }

}