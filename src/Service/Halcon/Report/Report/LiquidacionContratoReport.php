<?php


namespace App\Service\Halcon\Report\Report;


use App\Entity\Halcon\CabezaLiquidacion;
use App\Repository\Halcon\CabezaLiquidacionRepository;
use App\Service\Halcon\Report\Importer\LiquidacionContratoImporter;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class LiquidacionContratoReport extends Report
{
    private $noContrat;
    private $liqDefini;

    /**
     * @var CabezaLiquidacionRepository
     */
    private $cabezaLiquidacionRepo;

    public function __construct(PdfHandler $pdfHandler, LiquidacionContratoImporter $importer, CabezaLiquidacionRepository $cabezaLiquidacionRepo)
    {
        parent::__construct($pdfHandler, $importer);
        $this->cabezaLiquidacionRepo = $cabezaLiquidacionRepo;
    }

    public function renderPdf()
    {
        $certificado = $this->cabezaLiquidacionRepo->findLiquidacionContrato($this->usuario->getIdentificacion(), $this->noContrat, $this->liqDefini);
        $tercero = $this->terceroRepository->find($this->usuario->getIdentificacion());
        return $this->certificadoLaboralPdf->build($certificado, $tercero)->Output("S");
    }

    public function getPdfFileName(): string
    {
        // TODO: Implement getPdfFileName() method.
    }

    function renderMap()
    {
        foreach($this->cabezaLiquidacionRepo->findLiquidacionContrato($this->usuario->getIdentificacion()) as $liquidacionContrato) {
            if($liquidacionContrato->getVinculacion()) {
                yield $liquidacionContrato;
            }
        }
    }

    /**
     * @param mixed $noContrat
     * @return LiquidacionContratoReport
     */
    public function setNoContrat($noContrat)
    {
        $this->noContrat = $noContrat;
        return $this;
    }

    /**
     * @param mixed $liqDefini
     * @return LiquidacionContratoReport
     */
    public function setLiqDefini($liqDefini)
    {
        $this->liqDefini = $liqDefini;
        return $this;
    }


    /**
     * @param CabezaLiquidacion $reportEntity
     * @return array
     */
    function getIdentifier($reportEntity): array
    {
        return [
            $reportEntity->getVinculacion()->getNoContrat(),
            $reportEntity->getLiqDefini()
        ];
    }
}