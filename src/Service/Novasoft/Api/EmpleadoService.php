<?php


namespace App\Service\Novasoft\Api;


use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Api\Client\EmpleadoClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EmpleadoService
 * @package App\Service\Novasoft\Api
 * @deprecated
 */
class EmpleadoService
{
    /**
     * @var EmpleadoClient
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(EmpleadoClient $client, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder,
                                Configuracion $configuracion)
    {
        $this->client = $client;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->configuracion = $configuracion;
    }

    /**
     * Para personas sin usuario que se loguean y son empleados en novasoft creamos usuario
     * @param $ident
     * @return Empleado|null
     */
    public function ifEmpleadoCreateUsuario($ident): ?Empleado
    {
        $empleado = null;
        if(is_numeric($ident)) {
            $empleado = $this->update($ident);
        }
        return $empleado ?: null;
    }

    /**
     * Para usuarios nuevos que no son empleados, puede que ya lo sean en novasoft. Validamos y actualizamos si es el caso
     * @param Usuario $usuario
     * @return bool
     */
    public function ifEmpleadoAddRol(Usuario $usuario): bool
    {
        if (!$usuario->esRol(['/EMPLEADO/', '/SUPERADMIN/', '/CLIENTE/'])) {
            $this->update($usuario->getIdentificacion());
        }
        return $usuario->esRol('ROLE_EMPLEADO');
    }

    /**
     * @param string $identificacion
     * @return Empleado|null
     */
    public function update($identificacion): ?Empleado
    {
        $dbs = $this->configuracion->napi()->getDb();
        foreach($dbs as $db) {
            if ($empleado = $this->client->get($identificacion, $db)) {
                if (!$empleado->getId()) {
                    $this->prepareUsuario($empleado->getUsuario())
                        ->em->persist($empleado);
                    $this->em->flush();
                }
                $this->em->flush();
                return $empleado;
            }
        }
        return null;
    }

    private function prepareUsuario(Usuario $usuario): self
    {
        if(!$usuario->getId()) {
            $pass = substr($usuario->getIdentificacion(), -4);
            $encodedPass = $this->passwordEncoder->encodePassword($usuario, $pass);
            $usuario
                ->setPassword($encodedPass)
                ->aceptarTerminos();
        }
        $usuario->addRol('ROLE_EMPLEADO');
        return $this;
    }
}