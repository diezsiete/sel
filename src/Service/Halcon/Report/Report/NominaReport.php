<?php


namespace App\Service\Halcon\Report\Report;


use App\Repository\Halcon\Report\NominaRepository;
use App\Service\Pdf\Halcon\NominaPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class NominaReport extends Report
{
    private $noContrat;
    private $consecLiq;
    private $nitTercer;
    /**
     * @var NominaRepository
     */
    private $nominaRepo;
    /**
     * @var NominaPdf
     */
    private $nominaPdf;

    public function __construct(PdfHandler $pdfHandler, NominaRepository $nominaRepo, NominaPdf $nominaPdf)
    {
        parent::__construct($pdfHandler);
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

    /**
     * @param mixed $nitTercer
     * @return NominaReport
     */
    public function setNitTercer($nitTercer)
    {
        $this->nitTercer = $nitTercer;
        return $this;
    }

    public function renderPdf()
    {
        $nomina = $this->nominaRepo->findNomina($this->noContrat, $this->consecLiq, $this->nitTercer);
        return $this->nominaPdf->build($nomina[0])->Output("S");
    }

    public function getPdfFileName(): string
    {
        // TODO: Implement getPdfFileName() method.
    }
}