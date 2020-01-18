<?php


namespace App\Service\Halcon\Report\Importer;


use App\Entity\Halcon\CabezaLiquidacion;
use App\Entity\Halcon\CertificadoIngresos;
use App\Entity\ServicioEmpleados\CertificadoIngresos as SeCertificadoIngresos;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Repository\ServicioEmpleados\CertificadoIngresosRepository as SeCertificadoIngresosRepo;
use App\Repository\ServicioEmpleados\ReportRepository;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport;
use DateTime;
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

    /**
     * @param CertificadoIngresos $certificado
     * @return ServicioEmpleadosReport|null
     */
    protected function buildSeEntity($certificado): ?ServicioEmpleadosReport
    {
        return (new SeCertificadoIngresos())
            ->setPeriodo(DateTime::createFromFormat("Y-m-d", $certificado->getAno() . '-01-01'));
    }

    protected function getSeEntityRepo(): ReportRepository
    {
        return $this->seCertificadoIngresosRepo;
    }
}