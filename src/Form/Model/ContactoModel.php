<?php


namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;


class ContactoModel
{
    /**
     * @Assert\NotBlank(message="Ingrese su nombre")
     */
    public $nombre;

    /**
     * @Assert\NotBlank(message="Ingrese su correo")
     * @Assert\Email(message="Ingrese un email valido")
     */
    public $from;

    /**
     * @Assert\NotBlank(message="Ingrese mensaje")
     */
    public $mensaje;

    public $asunto = null;

    public $to;
}