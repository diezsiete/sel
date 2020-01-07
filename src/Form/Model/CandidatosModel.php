<?php


namespace App\Form\Model;

use App\Entity\Main\Usuario;
use App\Validator\IdentificacionUnica;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CandidatosModel
 * @package App\Form\Model
 * @IdentificacionUnica(path="identificacion")
 */
class CandidatosModel
{
    public $id;
    /**
     * @Assert\NotBlank(message="Por favor ingrese identificación")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     */
    public $identificacion;
    /**
     * @Assert\NotBlank(message="Por favor ingrese su nombre")
     */
    public $primerNombre;
    public $segundoNombre;
    /**
     * @Assert\NotBlank(message="Por favor ingrese su apellido")
     */
    public $primerApellido;
    public $segundoApellido;
    /**
     * @Assert\NotBlank(message="Por favor ingrese correo")
     * @Assert\Email(message="Ingrese un email valido")
     */
    public $email;
    /**
     * @Assert\NotBlank(message="Ingrese telefono")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     */
    public $telefono;
    /**
     * @Assert\NotBlank(message="Ingrese ciudad de residencia")
     */
    public $residencia;
    /**
     * @Assert\NotBlank(message="Ingrese su fecha de nacimiento")
     * @Assert\Date(message="Ingrese fecha valida de nacimiento")
     */
    public $nacimiento;

    public $aceptoTerminosEn;
    public $actividad;
    public $password;


    public function getUsuario(UserPasswordEncoderInterface $passwordEncoder): Usuario
    {
        $usuario = new Usuario();
        $usuario->setIdentificacion($this->identificacion)
            ->setPrimerNombre($this->primerNombre)
            ->setPrimerApellido($this->primerApellido)
            ->setSegundoApellido($this->segundoApellido)
            ->setEmail($this->email)
            ->setPassword($passwordEncoder->encodePassword($usuario, $this->password))
            ->setAceptoTerminosEn($this->aceptoTerminosEn);
        return $usuario;
    }
}