<?php


namespace App\Service\Halcon\Report\Report;


use App\Repository\Halcon\Report\NominaRepository;
use App\Service\Halcon\Report\Importer\NominaImporter;
use App\Service\Pdf\Halcon\NominaPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class NominaReport extends Report
{
    private $noContrat;
    private $consecLiq;

    /**
     * @var NominaRepository
     */
    private $nominaRepo;
    /**
     * @var NominaPdf
     */
    private $nominaPdf;

    public function __construct(PdfHandler $pdfHandler, NominaImporter $importer, NominaRepository $nominaRepo, NominaPdf $nominaPdf)
    {
        parent::__construct($pdfHandler, $importer);
        $this->nominaRepo = $nominaRepo;
        $this->nominaPdf = $nominaPdf;
    }

    /**
     * @param mixed $noContrat
     * @return NominaReport
     */
    public function setNoContrat($noContrat)
    {
        $this->noContrat = $noContrat;
        return $this;
    }

    /**
     * @param mixed $consecLiq
     * @return NominaReport
     */
    public function setConsecLiq($consecLiq)
    {
        $this->consecLiq = $consecLiq;
        return $this;
    }

    public function renderPdf()
    {
        $nomina = $this->nominaRepo->findNomina($this->noContrat, $this->consecLiq, $this->usuario->getIdentificacion());
        return $this->nominaPdf->build($nomina[0])->Output("S");
    }

    public function getPdfFileName(): string
    {
        $identificacion = $this->usuario->getIdentificacion();
        return "/halcon/nomina/$identificacion-$this->noContrat-$this->consecLiq.pdf";
    }

    function renderMap()
    {
        // TODO: Implement renderMap() method.
    }

    function getIdentifier($reportEntity): array
    {
        // TODO: Implement getIdentifier() method.
    }
}