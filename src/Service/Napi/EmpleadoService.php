<?php


namespace App\Service\Napi;


use App\Entity\Main\Usuario;
use App\Service\UsuarioService;

class EmpleadoService
{
    /**
     * @var UsuarioService
     */
    private $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    public function prepareNewUsuario(Usuario $usuario): self
    {
        $this->usuarioService->prepareNewUsuario($usuario);
        $usuario->addRol('ROLE_EMPLEADO');
        return $this;
    }
}