<?php


namespace App\Service\Novasoft\Report\Importer;

use App\Entity\Empleado;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class Importer
{
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
    private $fileImporter;

    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $propertyAccessor, FileImporter $fileImporter)
    {
        $this->em = $em;
        $this->propertyAccessor = $propertyAccessor;
        $this->fileImporter = $fileImporter;
    }

    public function import($result)
    {
        $result = is_array($result) ? $result : [$result];
        foreach($result as $entity) {
            $this->importEntity($entity);
        }
    }

    /**
     * @param string|array $reporteNombre
     * @param mixed $identifier
     * @param null|string $fecha
     * @param null|string $ext
     * @param null|mixed $result
     */
    public function importFile($reporteNombre, $identifier, $fecha = null, $ext = null, $result = null)
    {
        if(is_array($reporteNombre)) {
            $result = $identifier;
            list($reporteNombre, $identifier, $fecha, $ext) = $reporteNombre;
        }
        $this->fileImporter->write($reporteNombre, $identifier, $fecha, $ext, $result);
    }

    protected function importEntity($entity)
    {
        /** @var ClassMetadataInfo $targetMetadata */
        $targetMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity));
        foreach($targetMetadata->associationMappings as $mapping) {
            // TODO: trabajadores activos retorna convenio de base de datos, pero si no existe creo que genera error
            // debido a cascade
            // lo mismo pasa con empleado
            /*if($mapping['targetEntity'] === Convenio::class) {
                $entity->setConvenio($this->findConvenio($entity->getConvenio()));
            }*/

            if($mapping['type'] === ClassMetadataInfo::ONE_TO_ONE) {
                $this->handleOneToOne($entity, $mapping);
            }

            // trabajadoresActivos->centroCosto
            if($mapping['type'] === ClassMetadataInfo::MANY_TO_ONE) {
                $this->handleManyToOne($entity, $this->getParent($entity, $mapping), $mapping);
            }
        }

        $this->em->persist($entity);
        $this->em->flush();
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
                $this->em->remove($entityDb);
                $this->em->flush();
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
}