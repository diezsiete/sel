<?php


namespace App\Service\Halcon\Report\Importer;


use App\Entity\Halcon\CabezaLiquidacion;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Repository\ServicioEmpleados\CertificadoLaboralRepository as SeCertificadoLaboralRepo;
use App\Repository\ServicioEmpleados\ReportRepository;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CertificadoLaboralImporter extends Importer
{

    /**
     * @var LiquidacionContratoReport
     */
    protected $report;

    /**
     * @var SeCertificadoLaboralRepo
     */
    private $seCertificadoLaboralRepo;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher,
                                SeCertificadoLaboralRepo $seCertificadoLaboralRepo)
    {
        parent::__construct($em, $dispatcher);

        $this->seCertificadoLaboralRepo = $seCertificadoLaboralRepo;
    }


    protected function buildSeEntity($halconEntity): ServicioEmpleadosReport
    {
        //TODO
    }

    protected function getSeEntityRepo(): ReportRepository
    {
        return $this->seCertificadoLaboralRepo;
    }
}