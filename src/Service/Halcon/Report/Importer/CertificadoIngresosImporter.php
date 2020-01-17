<?php


namespace App\Service\Halcon\Report\Importer;


use App\Entity\Halcon\CabezaLiquidacion;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Repository\ServicioEmpleados\CertificadoIngresosRepository as SeCertificadoIngresosRepo;
use App\Repository\ServicioEmpleados\ReportRepository;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CertificadoIngresosImporter extends Importer
{

    /**
     * @var LiquidacionContratoReport
     */
    protected $report;

    /**
     * @var SeCertificadoIngresosRepo
     */
    private $seCertificadoIngresosRepo;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher,
                                SeCertificadoIngresosRepo $seCertificadoIngresosRepo)
    {
        parent::__construct($em, $dispatcher);

        $this->seCertificadoIngresosRepo = $seCertificadoIngresosRepo;
    }

    protected function buildSeEntity($halconEntity): ?ServicioEmpleadosReport
    {
        //TODO
    }

    protected function getSeEntityRepo(): ReportRepository
    {
        return $this->seCertificadoIngresosRepo;
    }
}