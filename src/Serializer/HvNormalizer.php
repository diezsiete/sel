<?php


namespace App\Serializer;


use App\Entity\Hv\Hv;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HvNormalizer extends HvEntityNormalizer implements NormalizerInterface
{

    public function normalize($object, $format = null, array $context = [])
    {
        $data = parent::normalize($object, $format, $context);
        $data += $data['usuario'];
        unset($data['usuario']);
        return $data;
    }


    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Hv;
    }

}