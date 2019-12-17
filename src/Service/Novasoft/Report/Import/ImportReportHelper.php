<?php


namespace App\Service\Novasoft\Report\Import;


use App\Entity\Convenio;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ImportReportHelper
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $propertyAccessor)
    {
        $this->em = $em;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function import($result)
    {
        $result = is_array($result) ? $result : [$result];
        foreach($result as $entity) {
            $this->importEntity($entity);
        }
    }

    private function importEntity($entity)
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

            // si hay relacion uno a uno, significa que es unica por lo que eliminamos el anterior registro
            if($mapping['type'] === ClassMetadataInfo::ONE_TO_ONE) {
                $entityDb = $this->em->getRepository(get_class($entity))->findOneBy([
                    $mapping['fieldName'] => $this->getIdentifier($this->propertyAccessor->getValue($entity, $mapping['fieldName']))
                ]);
                if($entityDb) {
                    $this->em->remove($entityDb);
                    $this->em->flush();
                }
            }
            // trabajadoresActivos->centroCosto
            if($mapping['type'] === ClassMetadataInfo::MANY_TO_ONE) {
                $parent = $this->findInDatabase($this->propertyAccessor->getValue($entity, $mapping['fieldName']));
                $this->propertyAccessor->setValue($entity, $mapping['fieldName'], $parent);
            }
        }
        $this->em->persist($entity);
        $this->em->flush();
    }

    private function findInDatabase($entity)
    {
        $entityDb = $this->em->getRepository(get_class($entity))->find($this->getIdentifier($entity));
        if(!$entityDb) {
            $this->em->persist($entity);
            $this->em->flush();
            $entityDb = $entity;
        }

        return $entityDb;
    }

    private function findConvenio(Convenio $convenio)
    {

    }

    private function getIdentifier($entity)
    {
        $targetMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity));
        $identifierMetadata = $targetMetadata->getIdentifier();
        if(count($identifierMetadata) > 1) {
            return new Exception("find in database by compound identifier not suported");
        }

        return $this->propertyAccessor->getValue($entity, $identifierMetadata[0]);
    }
}