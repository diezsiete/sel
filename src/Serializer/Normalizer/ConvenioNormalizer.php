<?php

namespace App\Serializer\Normalizer;

use App\Entity\Main\Convenio;
use App\Repository\Main\ConvenioRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ConvenioNormalizer extends ObjectNormalizer
{
    /**
     * @var ConvenioRepository
     */
    private $convenioRepo;

    /**
     * @required
     */
    public function setConvenioRepo(ConvenioRepository $convenioRepo)
    {
        $this->convenioRepo = $convenioRepo;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Convenio::class;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Convenio;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if($convenio = $this->convenioRepo->find($data['codigo'])) {
            $context['object_to_populate'] = $convenio;
        }
        return parent::denormalize($data, $class, $format, $context);
    }
}