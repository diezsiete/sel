<?php


namespace App\Service\Napi;


use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use App\Service\Configuracion\Configuracion;
use App\Service\Napi\Client\NapiClient;
use App\Service\UsuarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;

class EmpleadoService
{
    /**
     * @var UsuarioService
     */
    private $usuarioService;
    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var NapiClient
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UsuarioService $usuarioService, Configuracion $configuracion, NapiClient $client, EntityManagerInterface $em)
    {
        $this->usuarioService = $usuarioService;
        $this->configuracion = $configuracion;
        $this->client = $client;
        $this->em = $em;
    }

    /**
     * Para usuarios nuevos que no son empleados, puede que ya lo sean en novasoft. Validamos y actualizamos si es el caso
     * @param Usuario $usuario
     * @return bool
     */
    public function ifEmpleadoAddRol(Usuario $usuario): bool
    {
        if (!$usuario->esRol(['/EMPLEADO/', '/SUPERADMIN/', '/CLIENTE/'])) {
            $this->updateFromNapi($usuario->getIdentificacion());
        }
        return $usuario->esRol('ROLE_EMPLEADO');
    }

    /**
     * @param string $identificacion
     * @return Empleado|null
     */
    public function updateFromNapi($identificacion): ?Empleado
    {
        $dbs = $this->configuracion->napi()->getDb();
        foreach($dbs as $db) {
            /** @var Empleado|null $empleado */
            $empleado = $this->client->db($db)->itemOperations(Empleado::class)->get($identificacion);
            if($empleado) {
                try {
                    $this->importEmpleado($empleado);
                    return $empleado;
                }catch(\Exception $e) {
                    // TODO enviar un correo al administrador
                }
            }
        }
        return null;
    }

    /**
     * @param Empleado $empleado
     * @return Empleado
     * @throws Exception
     */
    public function importEmpleado(Empleado $empleado): Empleado
    {
        if($usuario = $empleado->getUsuario()) {
            if (!$usuario->getId()) {
                $this->prepareNewUsuario($empleado->getUsuario());
            }
            $usuario->addRol('ROLE_EMPLEADO');
            if (!$empleado->getId() || !$usuario->getId()) {
                $this->em->persist($empleado);
            }
            $this->em->flush();
            return $empleado;
        }
        throw new RuntimeException('importEmpleado, empleado no trae usuario');
    }

    public function prepareNewUsuario(Usuario $usuario): self
    {
        $this->usuarioService->prepareNewUsuario($usuario);
        $usuario->addRol('ROLE_EMPLEADO');
        return $this;
    }
}