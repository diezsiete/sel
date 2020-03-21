<?php

namespace App\Serializer\Normalizer;

use App\Entity\Main\Usuario;
use App\Repository\Main\UsuarioRepository;
use App\Service\Serializer\SerializeFunction;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UsuarioNormalizer extends ObjectNormalizer
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepo;
    /**
     * @var SerializeFunction
     */
    private $serializeFunction;

    /**
     * @required
     */
    public function setUsuarioRepo(UsuarioRepository $usuarioRepo)
    {
        $this->usuarioRepo = $usuarioRepo;
    }
    /**
     * @required
     */
    public function setSerializeFunction(SerializeFunction $serializeFunction)
    {
        $this->serializeFunction = $serializeFunction;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Usuario::class;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Usuario;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if($usuario = $this->usuarioRepo->findByIdentificacion($data['identificacion'])) {
            $context['object_to_populate'] = $usuario;
        }
        return parent::denormalize($data, $class, $format, $context);
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $context[AbstractNormalizer::CALLBACKS] = $this->serializeFunction->addCallbacks($object, $context);
        return parent::normalize($object, $format, $context);
    }
}