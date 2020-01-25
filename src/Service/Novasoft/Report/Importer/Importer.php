<?php


namespace App\Service\Novasoft\Report\Importer;

use App\Event\Event\LogEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Helper\Loggable;
use App\Service\Novasoft\Report\Report\Report;
use App\Service\ServicioEmpleados\Report\ImporterInterface;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use SSRS\SSRSReportException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class Importer implements ImporterInterface
{
    use Loggable;

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;
    /**
     * @var Report
     */
    protected $report;

    /**
     * @var bool
     */
    protected $update = true;
    /**
     * @var PdfHandler
     */
    private $pdfHandler;

    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $propertyAccessor,
                                PdfHandler $pdfHandler, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->propertyAccessor = $propertyAccessor;
        $this->dispatcher = $dispatcher;
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

    /**
     * @throws SSRSReportException
     */
    public function importMap()
    {
        $this->import($this->report->renderMap());
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

    /**
     * @throws SSRSReportException
     */
    public function importMapAndPdf()
    {
        $this->dispatcher->addListener(ImportEvent::class, [$this, 'importPdfListener']);
        $this->importMap();
        $this->dispatcher->removeListener(ImportEvent::class, [$this, 'importPdfListener']);
    }

    /**
     * @param bool $update
     * @return Importer
     */
    public function setUpdate(bool $update): Importer
    {
        $this->update = $update;
        return $this;
    }

    protected function import($result)
    {

        if($result) {
            $result = is_array($result) ? $result : [$result];
            foreach ($result as $entity) {
                $action = $this->importEntity($entity);
                $this->logImportEntity($entity, $action);
            }
        } else {
            $this->info("no hay reportes");
        }
    }


    protected function importEntity($entity)
    {
        $action = "inserted";
        $import = true;
        if($equal = $this->findEqual($entity)) {
            $action = "found equal";
            if($this->update) {
                $equalIdentifier = $this->getIdentifier($equal);
                $import = $this->handleEqual($equal);
                if ($import) {
                    $action .= ", deleted and inserted";
                    $this->dispatchDeleteEvent($equalIdentifier, get_class($entity));
                }
            } else {
                $import = false;
                $action .= ", doing nothing";
            }
        }

        if($import) {
            $this->em->persist($entity);
            $this->em->flush();
            $this->dispatchImportEvent($entity);
        }

        return $action;
    }

    protected function dispatchDeleteEvent($equalIdentifier, $entityClass)
    {
        $this->dispatcher->dispatch(new DeleteEvent($equalIdentifier, $entityClass));
    }

    protected function dispatchImportEvent($entity)
    {
        $this->dispatcher->dispatch(new ImportEvent($entity));
    }

    protected function getIdentifier($entity)
    {
        /** @var ClassMetadataInfo $targetMetadata */
        $targetMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity));
        $identifierMetadata = $targetMetadata->getIdentifier();
        if(count($identifierMetadata) > 1) {
            return new Exception("find in database by compound identifier not suported");
        }

        return $this->propertyAccessor->getValue($entity, $identifierMetadata[0]);
    }

    public function log($level, $message, array $context = array())
    {
        $this->dispatcher->dispatch(LogEvent::$level($message, $context));
    }

    protected function logImportEntity($entity, $action)
    {
        $this->info(sprintf("%s[%s] %s", get_class($entity), $this->getIdentifier($entity), $action));
    }

    public function importPdfListener(ImportEvent $event)
    {
        $this->report->setParametersByEntity($event->entity);
        $this->importPdf();
    }

    protected abstract function findEqual($entity);

    /**
     * Que hacer si findEqual encontro coincidencia exacta en base de datos
     * @param $equal
     * @return bool determina si importar o no
     */
    protected abstract function handleEqual($equal): bool;
}