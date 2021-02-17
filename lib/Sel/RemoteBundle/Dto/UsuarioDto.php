<?php


namespace Sel\RemoteBundle\Dto;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class UsuarioDto
{
    public $identificacion;

    public $roles;

    public $password;

    public $email;

    public $primerNombre;

    public $segundoNombre;

    public $primerApellido;

    public $segundoApellido;

    public $activo;

    public $aceptoTerminosEn;

    public $ultimoLogin;

    public $type;

    public $napidb;
}