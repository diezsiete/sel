<?php


namespace App\Serializer;


use App\Entity\Hv\Hv;

use App\Entity\Main\Usuario;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HvNormalizer extends HvEntityNormalizer implements DenormalizerInterface
{

    public function normalize($object, $format = null, array $context = [])
    {
        $data = parent::normalize($object, $format, $context);
        if(isset($data['usuario'])) {
            $data += $data['usuario'];
            unset($data['usuario']);
        }
        return $data;
    }


    public function supportsNormalization($data, $format = null)
    {
        return false;
        return $data instanceof Hv;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return false;
        return $type === Hv::class;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        // si es un post de api, aseguramos que nueva hv tenga un nuevo usuario
        if(isset($context['collection_operation_name']) && $context['collection_operation_name'] === 'post') {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = (new Hv())->setUsuario(new Usuario());
        }
        return $this->normalizer->denormalize($data, $class, $format, $context);
    }
}