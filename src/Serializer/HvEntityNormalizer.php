<?php


namespace App\Serializer;


use App\Entity\Hv;
use App\Entity\HvEntity;
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

    /**
     * @var ObjectNormalizer
     */
    protected $normalizer;
    /**
     * @var EntityManagerInterface
     */
    protected $em;


    public function __construct(ObjectNormalizer $normalizer, EntityManagerInterface $em)
    {
        $this->normalizer = $normalizer;
        $this->em = $em;
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

        $context += [
            AbstractNormalizer::CALLBACKS => $callbacks,
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true
        ];

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

        if(get_class($innerObject) === HvEntityNormalizer::class
            && (preg_match('/.*Dpto$/', $attributeName) || preg_match('/.*Ciudad$/', $attributeName))) {
            $data = ['id' => '00'];
        } else {
            $data = $this->normalizer->normalize($innerObject, $format, $context);
        }

        if(is_array($data)) {
            if(count($data) === 1) {
                $data = $data[array_key_first($data)];
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


}