<?php


namespace App\Service;


use App\Entity\Main\Usuario;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prepareNewUsuario(Usuario $usuario): self
    {
        if(!$usuario->getId()) {
            $pass = substr($usuario->getIdentificacion(), -4);
            $encodedPass = $this->passwordEncoder->encodePassword($usuario, $pass);
            $usuario
                ->setPassword($encodedPass)
                ->aceptarTerminos();
        }
        return $this;
    }
}