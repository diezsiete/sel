<?php

namespace App\Service\Serializer;

use App\Annotation\Serializer\NormalizeFunction;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class SerializeFunction
{
    protected static $loadedNormalizeFunction = [];

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(EntityManagerInterface $em, Reader $reader)
    {
        $this->em = $em;
        $this->reader = $reader;
    }

    public function addCallbacks($object, $context = [])
    {
        /** @var ClassMetadataInfo $targetMetadata */
        $targetMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($object));

        $callbacks = [];
        foreach($targetMetadata->fieldMappings as $mapping) {
            if ($this->hasNormalizeFunction($object, $mapping['fieldName'], $context)) {
                $callbacks[$mapping['fieldName']] = [$this, 'normalizeFunction'];
            }
        }
        return $callbacks;
    }

    public function normalizeFunction($innerObject, $outerObject, string $attributeName, string $format = null, array $context = [])
    {
        if($normalizeFunction = $this->getNormalizeFunction($outerObject, $attributeName, $context)) {
            return call_user_func($normalizeFunction->function, $innerObject);
        }
        return $innerObject;
    }

    protected function hasNormalizeFunction($object, $propertyName, $context = [])
    {
        return (bool)$this->getNormalizeFunction($object, $propertyName, $context);
    }

    /**
     * @param $object
     * @param $propertyName
     * @param array $context
     * @return NormalizeFunction|null
     */
    protected function getNormalizeFunction($object, $propertyName, $context = [])
    {
        $class = $this->em->getMetadataFactory()->getMetadataFor(get_class($object))->getName();
        if(!isset(static::$loadedNormalizeFunction[$class])) {
            $this->loadNormalizeFunction($object);
        }
        $normalizeFunction = static::$loadedNormalizeFunction[$class][$propertyName];
        if($normalizeFunction && isset($context['groups']) && $normalizeFunction->groups) {
            $normalizeFunction = array_intersect($context['groups'], $normalizeFunction->groups) ? $normalizeFunction : null;
        }
        return $normalizeFunction;
    }

    protected function loadNormalizeFunction($object)
    {
        /** @var ClassMetadataInfo $targetMetadata */
        $targetMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($object));
        $reflectionClass = $targetMetadata->getReflectionClass();
        foreach ($reflectionClass->getProperties() as $property) {
            $normalizeFunctionAnnotation = null;
            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if($annotation instanceof NormalizeFunction) {
                    $normalizeFunctionAnnotation = $annotation;
                }
            }
            static::$loadedNormalizeFunction[$targetMetadata->getName()][$property->getName()] = $normalizeFunctionAnnotation;
        }
    }
}