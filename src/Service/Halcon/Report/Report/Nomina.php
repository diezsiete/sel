<?php


namespace App\Service\Halcon\Report\Report;


use App\Repository\Halcon\Report\NominaRepository;
use App\Service\Pdf\Halcon\NominaPdf;

class Nomina extends Report
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

    public function __construct(NominaRepository $nominaRepo, NominaPdf $nominaPdf)
    {
        $this->nominaRepo = $nominaRepo;
        $this->nominaPdf = $nominaPdf;
    }

    public function renderPdf()
    {
        $nomina = $this->nominaRepo->findNomina($this->noContrat, $this->consecLiq, $this->nitTercer);
        return $this->nominaPdf->build($nomina[0])->Output("S");
    }

    /**
     * @param mixed $noContrat
     * @return Nomina
     */
    public function setNoContrat($noContrat)
    {
        $this->noContrat = $noContrat;
        return $this;
    }

    /**
     * @param mixed $consecLiq
     * @return Nomina
     */
    public function setConsecLiq($consecLiq)
    {
        $this->consecLiq = $consecLiq;
        return $this;
    }

    /**
     * @param mixed $nitTercer
     * @return Nomina
     */
    public function setNitTercer($nitTercer)
    {
        $this->nitTercer = $nitTercer;
        return $this;
    }


}