<?php


namespace App\Service\Halcon\Report\Report;


use App\Entity\Halcon\CabezaLiquidacion;
use App\Repository\Halcon\CabezaLiquidacionRepository;
use App\Repository\Halcon\TerceroRepository;
use App\Service\Halcon\Report\Importer\LiquidacionContratoImporter;
use App\Service\Pdf\Halcon\LiquidacionContratoPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use Generator;

class LiquidacionContratoReport extends Report
{
    private $noContrat;
    private $liqDefini;

    /**
     * @var CabezaLiquidacionRepository
     */
    private $cabezaLiquidacionRepo;
    /**
     * @var LiquidacionContratoPdf
     */
    private $liquidacionContratoPdf;

    public function __construct(PdfHandler $pdfHandler, LiquidacionContratoImporter $importer,
                                CabezaLiquidacionRepository $cabezaLiquidacionRepo, LiquidacionContratoPdf $liquidacionContratoPdf)
    {
        parent::__construct($pdfHandler, $importer);
        $this->cabezaLiquidacionRepo = $cabezaLiquidacionRepo;
        $this->liquidacionContratoPdf = $liquidacionContratoPdf;
    }

    public function renderPdf()
    {
        $certificado = $this->cabezaLiquidacionRepo->findLiquidacionContrato($this->usuario->getIdentificacion(), $this->noContrat, $this->liqDefini);
        return $this->liquidacionContratoPdf->build($certificado)->Output("S");
    }

    public function getPdfFileName(): string
    {
        return "/halcon/liquidacion-contrato/{$this->usuario->getIdentificacion()}-$this->noContrat-$this->liqDefini.pdf";
    }

    /**
     * @return Generator<CabezaLiquidacion>
     */
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