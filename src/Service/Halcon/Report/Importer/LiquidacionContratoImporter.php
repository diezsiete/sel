<?php


namespace App\Service\Halcon\Report\Importer;


use App\Entity\Halcon\CabezaLiquidacion;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Repository\ServicioEmpleados\LiquidacionContratoRepository as SeLiquidacionContratoRepository;
use App\Repository\ServicioEmpleados\ReportRepository;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class LiquidacionContratoImporter extends Importer
{

    /**
     * @var LiquidacionContratoReport
     */
    protected $report;

    /**
     * @var SeLiquidacionContratoRepository
     */
    private $seLiquidacionContratoRepo;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher,
                                SeLiquidacionContratoRepository $seLiquidacionContratoRepo)
    {
        parent::__construct($em, $dispatcher);
        $this->seLiquidacionContratoRepo = $seLiquidacionContratoRepo;
    }

    /**
     * @param CabezaLiquidacion $halconEntity
     * @return ServicioEmpleadosReport
     */
    protected function buildSeEntity($halconEntity): ?ServicioEmpleadosReport
    {
        return (new LiquidacionContrato())
            ->setFechaIngreso($halconEntity->getIngreso())
            ->setFechaRetiro($halconEntity->getLiquidacio())
            ->setContrato($halconEntity->getVinculacion()->getNoContrat());
    }

    protected function getSeEntityRepo(): ReportRepository
    {
        return $this->seLiquidacionContratoRepo;
    }
}