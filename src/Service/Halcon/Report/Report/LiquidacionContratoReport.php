<?php


namespace App\Service\Halcon\Report\Report;


use App\Entity\Halcon\CabezaLiquidacion;
use App\Repository\Halcon\CabezaLiquidacionRepository;
use App\Service\Halcon\Report\Importer\LiquidacionContratoImporter;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class LiquidacionContratoReport extends Report
{

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
        // TODO: Implement renderPdf() method.
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
     * @param CabezaLiquidacion $reportEntity
     * @return array
     */
    function getIdentifier($reportEntity): array
    {
        return [
            $reportEntity->getVinculacion()->getNoContrat(),
            $reportEntity->getLiqDefini(),
            $reportEntity->getAuxiliar()
        ];
    }
}