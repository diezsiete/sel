<?php


namespace App\Form\DataTransformer;

use App\Repository\UsuarioRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RestaurarClaveIdentificacionTransformer implements DataTransformerInterface
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function transform($value)
    {
        return $value ? $value->getIdentificacion() : $value;
    }

    public function reverseTransform($value)
    {
        $usuario = null;
        if($value) {
            $usuario = $this->usuarioRepository->findByIdentificacion($value);
            if(!$usuario) {
                throw new TransformationFailedException("Usuario no encontrado");
            }
        }

        return $usuario;
    }
}