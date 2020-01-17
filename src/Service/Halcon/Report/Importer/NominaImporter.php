<?php


namespace App\Service\Halcon\Report\Importer;



use App\Entity\ServicioEmpleados\Nomina;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Repository\ServicioEmpleados\NominaRepository as SeNominaRepo;
use App\Repository\ServicioEmpleados\ReportRepository;
use App\Service\Halcon\Report\Report\LiquidacionContratoReport;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class NominaImporter extends Importer
{

    /**
     * @var LiquidacionContratoReport
     */
    protected $report;

    /**
     * @var SeNominaRepo
     */
    private $seNominaRepo;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher,
                                SeNominaRepo $seNominaRepo)
    {
        parent::__construct($em, $dispatcher);

        $this->seNominaRepo = $seNominaRepo;
    }

    protected function buildSeEntity($halconEntity): ?ServicioEmpleadosReport
    {
        if($halconEntity['fecha'] === 'undefined') {
            return null;
        }
        return (new Nomina())
            ->setFecha(DateTime::createFromFormat('Y-m-d', $halconEntity['fecha']))
            ->setConvenio($halconEntity['empresa'] ? $halconEntity['empresa'] : $halconEntity['compania']);
    }

    protected function getSeEntityRepo(): ReportRepository
    {
        return $this->seNominaRepo;
    }
}