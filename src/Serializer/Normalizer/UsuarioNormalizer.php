<?php

namespace App\Serializer\Normalizer;

use App\Entity\Main\Usuario;
use App\Repository\Main\UsuarioRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UsuarioNormalizer extends ObjectNormalizer
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepo;

    /**
     * @required
     */
    public function setUsuarioRepo(UsuarioRepository $usuarioRepo)
    {
        $this->usuarioRepo = $usuarioRepo;
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
}