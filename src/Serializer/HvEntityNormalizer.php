<?php


namespace App\Serializer;


use App\Annotation\Serializer\NormalizeFunction;
use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use Doctrine\Common\Annotations\Reader;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HvEntityNormalizer implements NormalizerInterface
{
    protected static $loadedNormalizeFunction = [];

    /**
     * @var ObjectNormalizer
     */
    protected $normalizer;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var Reader
     */
    private $reader;



    public function __construct(ObjectNormalizer $normalizer, EntityManagerInterface $em, Reader $reader)
    {
        $this->normalizer = $normalizer;
        $this->em = $em;
        $this->reader = $reader;
    }

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param HvEntity $object Object to normalize
     * @param string $format Format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool
     *
     * @throws InvalidArgumentException   Occurs when the object given is not an attempted type for the normalizer
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it
     * @throws LogicException             Occurs when the normalizer is not called in an expected context
     * @throws ExceptionInterface         Occurs for all the other cases of errors
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var ClassMetadataInfo $targetMetadata */
        $targetMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($object));

        $callbacks = [];
        foreach($targetMetadata->fieldMappings as $mapping) {
            if($mapping['type'] === Type::DATE) {
                $callbacks[$mapping['fieldName']] = [$this, 'normalizeDate'];
            }
            if ($this->hasNormalizeFunction($object, $mapping['fieldName'])) {
                $callbacks[$mapping['fieldName']] = [$this, 'normalizeFunction'];
            }
        }

        foreach($targetMetadata->associationMappings as $mapping) {
            // relaciones hv child o usuario no normalizamos
            if($mapping['type'] !== ClassMetadataInfo::ONE_TO_MANY && $mapping['type'] !== ClassMetadataInfo::ONE_TO_ONE) {
                $callbacks[$mapping['fieldName']] = [$this, 'normalizeAssociation'];
            }
        }


        if(isset($context['scraper-hv-child'])) {
            $scraperHvChild = $context['scraper-hv-child'];
            $childClass = is_object($scraperHvChild) ? get_class($scraperHvChild) : $scraperHvChild;
            foreach($targetMetadata->associationMappings as $mapping) {
                if($mapping['targetEntity'] === $childClass ) {
                    $context['groups'][] = $mapping['fieldName'];
                    if(is_object($scraperHvChild)) {
                        $callbacks[$mapping['fieldName']] = [$this, 'filterChild'];
                    }
                }
            }
        }

        $context[AbstractNormalizer::CALLBACKS] = $callbacks;
        $context[AbstractObjectNormalizer::SKIP_NULL_VALUES] = true;

        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof HvEntity && !($data instanceof  Hv);
    }

    public function normalizeDate($innerObject, $outerObject, string $attributeName, string $format = null, array $context = [])
    {
        return $innerObject instanceof \DateTime ? $innerObject->format(('Y-m-d')) : null;
    }

    public function normalizeFunction($innerObject, $outerObject, string $attributeName, string $format = null, array $context = [])
    {
        if($normalizeFunction = $this->getNormalizeFunction($outerObject, $attributeName, $context)) {
            return call_user_func($normalizeFunction->function, $innerObject);
        }
        return $innerObject;
    }

    /**
     * Normaliza objetos de un solo valor
     */
    public function normalizeAssociation($innerObject, $outerObject, string $attributeName, string $format = null, array $context = [])
    {
        //si selecciono un pais sin dpto y ciudad o un dpto sin ciudad el valor null se convierte en 00 para novasoft
        if(!$innerObject && (preg_match('/.*Dpto$/', $attributeName) || preg_match('/.*Ciudad$/', $attributeName))) {
            $data = ['id' => '00'];
        } else {
            $data = $this->normalizer->normalize($innerObject, $format, $context);
        }

        if(is_array($data)) {
            if(count($data) === 1) {
                $data = $data[array_key_first($data)];
                // napi:hv-child:post hv => num ident
                if(is_array($data) && count($data) === 1) {
                    $data = $data[array_key_first($data)];
                }
            } else {
                $data = $data ? $data : null;
            }
        }

        return $data;
    }

    public function filterChild($innerObject, $outerObject, string $attributeName, string $format = null, array $context = [])
    {
        $response = [];
        foreach($innerObject as $child) {
            if($child->getId() === $context['scraper-hv-child']->getId()) {
                 $response[] = self::normalize($child, $format, $context);
            }
        }
        return $response;
    }

    protected function hasNormalizeFunction($object, $propertyName)
    {
        return (bool)$this->getNormalizeFunction($object, $propertyName);
    }

    /**
     * @param $object
     * @param $propertyName
     * @param array $context
     * @return NormalizeFunction|null
     */
    protected function getNormalizeFunction($object, $propertyName, array $context = [])
    {
        $class = get_class($object);
        if(!isset(static::$loadedNormalizeFunction[$class])) {
            $this->loadNormalizeFunction($object);
        }
        $normalizeFunction = static::$loadedNormalizeFunction[$class][$propertyName];
        if(isset($context['groups']) && $normalizeFunction->groups) {
            $normalizeFunction = array_intersect($context['groups'], $normalizeFunction->groups) ? $normalizeFunction : null;
        }
        return $normalizeFunction;
    }

    protected function loadNormalizeFunction($object)
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        foreach ($reflectionClass->getProperties() as $property) {
            $normalizeFunctionAnnotation = null;
            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if($annotation instanceof NormalizeFunction) {
                    $normalizeFunctionAnnotation = $annotation;
                }
            }
            static::$loadedNormalizeFunction[$reflectionClass->getName()][$property->getName()] = $normalizeFunctionAnnotation;
        }
    }

}