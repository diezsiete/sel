<?php


namespace App\Service\Halcon\Report\Report;


use App\Entity\Main\Usuario;
use App\Service\Halcon\Report\Importer\Importer;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\ServicioEmpleados\Report\ReportInterface;

abstract class Report implements ReportInterface
{
    /**
     * @var Usuario
     */
    protected $usuario;

    /**
     * @var PdfHandler
     */
    protected $pdfHandler;

    /**
     * @var Importer
     */
    protected $importer;

    public function __construct(PdfHandler $pdfHandler, Importer $importer)
    {
        $this->pdfHandler = $pdfHandler;
        $this->importer = $importer->setReport($this);
    }

    public function streamPdf()
    {
        // usar cacheAndStream si se quiere cache
        return $this->pdfHandler->cacheAndStream($this->getPdfFileName(), function () {
            return $this->renderPdf();
        });

    }

    /**
     * @return Importer
     */
    public function getImporter(): Importer
    {
        return $this->importer;
    }

    /**
     * @return Usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     * @return $this
     */
    public function setUsuario(Usuario $usuario): Report
    {
        $this->usuario = $usuario;
        return $this;
    }


    abstract function renderMap();

    abstract function getIdentifier($reportEntity): array;
}