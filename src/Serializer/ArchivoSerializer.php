<?php

namespace App\Serializer;

use App\Entity\Archivo\Archivo;
use App\Service\Utils;
use DateTime;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ArchivoSerializer
 * @package App\Serializer
 * Para poder transformar atributos para frontend
 */
class ArchivoSerializer implements NormalizerInterface
{
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;
    /**
     * @var Utils
     */
    private $utils;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ObjectNormalizer $normalizer, Utils $utils, RouterInterface $router)
    {
        $this->normalizer = $normalizer;
        $this->utils = $utils;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $context[AbstractNormalizer::CALLBACKS] = [
            'size' => function ($size) {
                return $this->utils->byteToSize($size);
            },
            'createdAt' => [$this, 'dateFormat'],
            'updatedAt' => [$this, 'dateFormat'],
            'createdAtFull' => [$this, 'dateFullFormat'],
            'updatedAtFull' => [$this, 'dateFullFormat']
        ];

        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Archivo;
    }

    public function dateFormat($date)
    {
        return $date instanceof DateTime
            ? "{$this->utils->meses($date->format('n') - 1)} {$date->format('d')}, {$date->format('Y')}"
            : '';
    }

    /**
     * @param DateTime|null $date
     * @return string
     */
    public function dateFullFormat($date)
    {
        $dateFormatted = $this->dateFormat($date);
        if($dateFormatted) {
            $dateFormatted .= ', ' . $date->format('H:i:s');
        }
        return $dateFormatted;
    }
}