<?php


namespace App\Service\Novasoft\Report\Importer;

use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Event\Event\Novasoft\Report\Importer\ImporterLogEvent;
use App\Event\Event\Novasoft\Report\Importer\TestEvent;
use App\Helper\Loggable;
use App\Service\Novasoft\Report\Report\Report;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class Importer
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
     * @var FileImporter
     */
    protected $fileImporter;
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

    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $propertyAccessor,
                                FileImporter $fileImporter, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->propertyAccessor = $propertyAccessor;
        $this->fileImporter = $fileImporter;
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
        $this->import($this->report->renderMap());
    }

    public function importPdf()
    {
        $this->importFile($this->report->getPdfFileName(), $this->report->renderPdf());
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

    /**
     * @param string|array $reporteNombre
     * @param mixed $identifier
     * @param null|string $fecha
     * @param null|string $ext
     * @param null|mixed $result
     */
    protected function importFile($reporteNombre, $identifier, $fecha = null, $ext = null, $result = null)
    {
        if(is_array($reporteNombre)) {
            $result = $identifier;
            list($reporteNombre, $identifier, $fecha, $ext) = $reporteNombre;
        }
        $this->fileImporter->write($reporteNombre, $identifier, $fecha, $ext, $result);
    }

    protected function importEntity($entity)
    {
        $action = "inserted";
        $import = true;
        if($equal = $this->findEqual($entity)) {
            $action = "found equal";
            if($this->update) {
                $import = $this->handleEqual($equal);
                if ($import) {
                    $action .= ", deleted and inserted";
                    $this->dispatchDeleteEvent($equal);
                }
            } else {
                $import = false;
                $action .= ", doing nothing";
            }
        }

        if($import) {
            /** @var ClassMetadataInfo $targetMetadata */
            /*$targetMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity));
            foreach ($targetMetadata->associationMappings as $mapping) {


                if ($mapping['type'] === ClassMetadataInfo::ONE_TO_ONE) {
                    //$this->handleOneToOne($entity, $mapping);
                }

                // trabajadoresActivos->centroCosto
                if ($mapping['type'] === ClassMetadataInfo::MANY_TO_ONE) {
                    //$this->handleManyToOne($entity, $this->getParent($entity, $mapping), $mapping);
                }
            }*/
            $this->em->persist($entity);
            $this->em->flush();
            $this->dispatchImportEvent($entity);
        }

        return $action;
    }

    protected function dispatchDeleteEvent($entity)
    {
        $identifier = $this->getIdentifier($this->getIdentifier($entity));
        $this->dispatcher->dispatch(new DeleteEvent($identifier, get_class($entity)));
    }

    protected function dispatchImportEvent($entity)
    {
        $this->dispatcher->dispatch(new ImportEvent($entity));
    }

    protected function handleManyToOne($entity, $parent, $mapping)
    {
        $parentIdentifier = $this->getIdentifier($parent);
        $parentDb = $parentIdentifier ? $this->em->getRepository(get_class($parent))->find($parentIdentifier) : null;
        if (!$parentDb) {
            $this->importEntity($parent);
            $parentDb = $parent;
        }
        $this->propertyAccessor->setValue($entity, $mapping['fieldName'], $parentDb);
    }

    protected function handleOneToOne($entity, $mapping)
    {
        // si es owning side (ej. trabajador activo -> empleado) significa que es unica por lo que eliminamos el anterior registro
        if($mapping['isOwningSide']) {
            $entityDb = $this->em->getRepository(get_class($entity))->findOneBy([
                $mapping['fieldName'] => $this->getIdentifier($this->propertyAccessor->getValue($entity, $mapping['fieldName']))
            ]);
            if ($entityDb) {
                //$this->em->remove($entityDb);
                //$this->em->flush();
            }
        }
        //no es owning side (ej. liquidacionNomina -> total) no hay que eliminarla
        else if(!$mapping['cascade']) {
            throw new Exception("La asociacion oneToOne {$mapping['targetEntity']} no es manejada");
        }

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


    protected function getParent($entity, $mapping)
    {
        $parent = $this->propertyAccessor->getValue($entity, $mapping['fieldName']);
        if(!$parent) {
            throw new Exception("relacion many to one parent is null");
        }
        return $parent;
    }

    public function log($level, $message, array $context = array())
    {
        $this->dispatcher->dispatch(ImporterLogEvent::$level($message, $context));
    }

    protected function logImportEntity($entity, $action)
    {
        $this->info(sprintf("%s %s", $this->getIdentifier($entity), $action));
    }


    protected abstract function findEqual($entity);

    /**
     * Que hacer si findEqual encontro coincidencia exacta en base de datos
     * @param $equal
     * @return bool determina si importar o no
     */
    protected abstract function handleEqual($equal): bool;
}