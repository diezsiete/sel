<?php


namespace App\Service\Halcon\Report\Importer;



use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Helper\Loggable;
use App\Repository\ServicioEmpleados\ReportRepository;
use App\Service\Halcon\Report\Report\Report;
use App\Service\ServicioEmpleados\Report\ImporterInterface;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


abstract class Importer implements ImporterInterface
{
    use Loggable;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Report
     */
    protected $report;
    /**
     * @var PdfHandler
     */
    private $pdfHandler;

    /**
     * @var bool
     */
    private $importPdfFlag = false;

    public function __construct(EntityManagerInterface $em, PdfHandler $pdfHandler)
    {
        $this->em = $em;
        $this->pdfHandler = $pdfHandler;
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

    public function importPdf()
    {
        $this->pdfHandler->write($this->report->getPdfFileName(), function () {
            return $this->report->renderPdf();
        });
    }

    public function deletePdf()
    {
        return $this->pdfHandler->delete($this->report->getPdfFileName());
    }

    public function importMapAndPdf()
    {
        $this->importPdfFlag = true;
        $this->importMap();
        $this->importPdfFlag = false;
    }


    protected function importEntity($halconEntity)
    {
        $seEntity = $this->buildSeEntity($halconEntity);
        if($seEntity) {
            $seEntityEqual = $this->getSeEntityRepo()->findBySourceId(
                $seEntity->getSource(),
                $seEntity->getUsuario()->getIdentificacion(),
                $seEntity->getSourceId()
            );

            if ($seEntityEqual) {
                $this->em->remove($seEntityEqual);
            }

            $this->em->persist($seEntity);
            $this->em->flush();

            if($this->importPdfFlag) {
                //$this->report->setParametersBySeEntity($seEntity);
            }
        }
    }

    public abstract function buildSeEntity($halconEntity): ?ServicioEmpleadosReport;


    protected abstract function getSeEntityRepo(): ReportRepository;

}