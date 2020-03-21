<?php


namespace App\Serializer;


use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Service\Serializer\SerializeFunction;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
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
    /**
     * @var ObjectNormalizer
     */
    protected $normalizer;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var SerializeFunction
     */
    private $serializeFunction;
    /**
     * @var IriConverterInterface
     */
    private $iriConverter;
    /**
     * @var ResourceClassResolverInterface
     */
    private $resourceClassResolver;

    public function __construct(ObjectNormalizer $normalizer, EntityManagerInterface $em,
                                SerializeFunction $serializeFunction,
                                IriConverterInterface $iriConverter, ResourceClassResolverInterface $resourceClassResolver)
    {
        $this->normalizer = $normalizer;
        $this->em = $em;
        $this->serializeFunction = $serializeFunction;
        $this->iriConverter = $iriConverter;
        $this->resourceClassResolver = $resourceClassResolver;
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

        $callbacks = $this->serializeFunction->addCallbacks($object, $context);

        foreach($targetMetadata->fieldMappings as $mapping) {
            if($mapping['type'] === Type::DATE) {
                $callbacks[$mapping['fieldName']] = [$this, 'normalizeDate'];
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

    /**
     * Normaliza objetos de un solo valor
     */
    public function normalizeAssociation($innerObject, $outerObject, string $attributeName, string $format = null, array $context = [])
    {
        // api-platform interfiere en normalizacion y desnormalizacion de resources, configuramos para que funcione
        if(is_object($innerObject) && isset($context['groups']) && in_array('messenger:hv-child:put', $context['groups'])
            && (is_object($outerObject) && $this->resourceClassResolver->isResourceClass(get_class($outerObject)))) {
            return $this->iriConverter->getIriFromItem($innerObject);
        }

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
                 $response[] = $this->normalize($child, $format, $context);
            }
        }
        return $response;
    }



}