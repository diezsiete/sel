<?php


namespace App\Serializer\Napi;

use App\Annotation\NapiClient\NapiResource;
use App\Annotation\NapiClient\NapiResourceId;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class NapiResourceDenormalizer extends ObjectNormalizer
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $resourceIds = [];

    /**
     * @required
     */
    public function setReader(Reader $reader)
    {
        $this->reader = $reader;
    }
    /**
     * @required
     */
    public function setEm(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        // denormalizar coleccion (TODO no se si sea la mejor opcion)
        if(isset($data['@type'], $data['hydra:member']) && $data['@type'] === 'hydra:Collection') {
            $collection = [];
            foreach($data['hydra:member'] as $member) {
                $collection[] = $this->denormalize($member, $class, $format, $context);
            }
            return $collection;
        }

        // denormailizar objeto
        $identifier = $this->buildIdentifier($class, $data);
        $dbEntity = $this->em->getRepository($class)->findOneBy($identifier);
        if($dbEntity) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $dbEntity;
        }
        return parent::denormalize($data, $class, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return (bool)$this->reader->getClassAnnotation(new \ReflectionClass($type), NapiResource::class);
    }

    public function supportsNormalization($data, $format = null)
    {
        // para que no se entrometa como normalizer de otros objetos que no le corresponden. Sucedia con Usuario
        return false;
    }

    private function buildIdentifier($class, $data)
    {
        $identifier = [];
        $resourceIds = $this->getResourceIds($class);
        foreach($resourceIds as $mapping) {
            $value = $data[$mapping['fieldName']];
            if($mapping['type'] === 'date' || $mapping['type'] === 'datetime') {
                $value = new DateTime($value);
            }
            $identifier[$mapping['fieldName']] = $value;
        }
        return $identifier;
    }

    /**
     * @param $class
     * @return array
     * @throws ReflectionException
     */
    private function getResourceIds($class)
    {
        if(!isset($this->resourceIds[$class])) {
            $ids = [];
            /** @var ClassMetadataInfo $targetMetadata */
            $targetMetadata = $this->em->getMetadataFactory()->getMetadataFor($class);
            foreach ($targetMetadata->fieldMappings as $mapping) {
                $reflectionProperty = new ReflectionProperty($class, $mapping['fieldName']);
                if ($resourceIdAnnotation = $this->reader->getPropertyAnnotation($reflectionProperty, NapiResourceId::class)) {
                    $ids[] = $mapping;
                }
            }
            $this->resourceIds[$class] = $ids;
        }
        return $this->resourceIds[$class];
    }
}