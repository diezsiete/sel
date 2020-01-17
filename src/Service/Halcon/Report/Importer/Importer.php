<?php


namespace App\Service\Halcon\Report\Importer;



use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Helper\Loggable;
use App\Repository\ServicioEmpleados\ReportRepository;
use App\Service\Halcon\Report\Report\Report;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


abstract class Importer
{
    use Loggable;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var Report
     */
    protected $report;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Report $report
     * @return $this
     */
    public function setReport(Report $report)
    {
        $this->report = $report;
        return $this;
    }

    public function importMap()
    {
        $result = false;
        foreach ($this->report->renderMap() as $entity) {
            $this->importEntity($entity);
            $result = true;
        }
        if(!$result) {
            $this->info("no hay reportes");
        }
    }


    protected function importEntity($halconEntity)
    {
        $seEntity = $this->buildSeEntity($halconEntity);
        $seEntity
            ->setUsuario($this->report->getUsuario())
            ->setSourceHalcon()
            ->setSourceId(implode(',', $this->report->getIdentifier($halconEntity)));


        $seEntityEqual = $this->getSeEntityRepo()->findBySourceId(
            $seEntity->getSource(),
            $seEntity->getUsuario()->getIdentificacion(),
            $seEntity->getSourceId()
        );

        if($seEntityEqual) {
            $this->em->remove($seEntityEqual);
        }

        $this->em->persist($seEntity);
        $this->em->flush();
    }

    protected abstract function buildSeEntity($halconEntity): ServicioEmpleadosReport;


    protected abstract function getSeEntityRepo(): ReportRepository;

}