<?php


namespace App\Service\Novasoft;


use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use App\Repository\Main\EmpleadoRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\ReportesServicioEmpleados;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class NovasoftEmpleadoService
{
    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var ReportesServicioEmpleados
     */
    private $reportesServicioEmpleados;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    private $lastSsrsDb;
    /**
     * @var string
     */
    private $appEnv;

    public function __construct(Configuracion $configuracion, ReportesServicioEmpleados $reportesServicioEmpleados, $appEnv,
                                EmpleadoRepository $empleadoRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->configuracion = $configuracion;
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
        $this->empleadoRepository = $empleadoRepository;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->appEnv = $appEnv;
    }


    public function updateEmpleado($ident)
    {
        try {
            if(is_numeric($ident)) {
                if ($empleadoNovasoft = $this->findInNovasoft($ident)) {
                    return $this->importEmpleado($empleadoNovasoft, $this->lastSsrsDb);
                }
            }
            return false;
        } catch (Exception $e) {
            if($this->appEnv === 'dev') {
                throw $e;
            }
            return false;
        }
    }

    /**
     * Para usuarios nuevos que no son empleados, puede que ya lo sean en novasoft. Validamos y actualizamos si es el caso
     * @param Usuario $usuario
     * @return bool
     */
    public function addRoleEmpleadoToUsuario(Usuario $usuario)
    {
        try {
            if (!$usuario->esRol(['/EMPLEADO/', '/SUPERADMIN/', '/CLIENTE/'])) {
                if ($empleadoNovasoft = $this->findInNovasoft($usuario->getIdentificacion())) {
                    $this->importEmpleado($empleadoNovasoft, $this->lastSsrsDb);
                }
            }
        } catch (Exception $e) {
            if($this->appEnv === 'dev') {
                throw $e;
            }
            return null;
        }
        return $usuario->esRol('ROLE_EMPLEADO');
    }

    /**
     * @param $ident
     * @return bool|Empleado
     * @throws Exception
     */
    public function findInNovasoft($ident)
    {
        if(is_numeric($ident)) {
            $ssrsDbs = $this->configuracion->getSsrsDb();
            foreach ($ssrsDbs as $ssrsDb) {
                if ($empleadoNovasoft = $this->reportesServicioEmpleados->setSsrsDb($ssrsDb->getNombre())->getEmpleado($ident)) {
                    $this->lastSsrsDb = $ssrsDb->getNombre();
                    return $empleadoNovasoft[0];
                }
            }
        }
        return false;
    }

    /**
     * @param string $identificacion
     * @return string|null
     * @throws Exception
     */
    public function getSsrsDb($identificacion)
    {
        if(count($this->configuracion->getSsrsDb()) === 1) {
            return $this->configuracion->getSsrsDb()[0]->getNombre();
        }

        $empleado = $this->empleadoRepository->findByIdentificacion($identificacion);
        if($empleado) {
            return $empleado->getSsrsDb();
        } else {
            return $this->configuracion->getSsrsDb()[0]->getNombre();
        }
    }

    private function importEmpleado(Empleado $empleado, string $ssrsDb)
    {
        $usuario = $this->updateUsuario($empleado->getUsuario());
        //$usuarioMessage = "[usuario update]";
        if (!$usuario) {
            $usuario = $this->insertUsuario($empleado->getUsuario());
            //$usuarioMessage = "[usuario insert]";
        }

        $empleado->setUsuario($usuario);
        $empleado->setSsrsDb($ssrsDb);

        //$empleadoMessage = "[empleado update]";
        $empleadoDatabase = $this->updateEmpleadoDatabase($empleado);
        if (!$empleadoDatabase) {
            $this->insertEmpleadoDatabase($empleado);
            //$empleadoMessage = "[empleado insert]";
        } else {
            $empleado = $empleadoDatabase;
        }

        $this->em->flush();

        return $empleado;
    }


    private function updateEmpleadoDatabase(Empleado $empleado)
    {
        $updated = false;
        $empleadoDb = $this->empleadoRepository->findByIdentificacion($empleado->getUsuario()->getIdentificacion());
        if ($empleadoDb) {
            $empleadoDb
                ->setSexo($empleado->getSexo())
                ->setEstadoCivil($empleado->getEstadoCivil())
                ->setHijos($empleado->getHijos())
                ->setNacimiento($empleado->getNacimiento())
                ->setTelefono1($empleado->getTelefono1())
                ->setTelefono2($empleado->getTelefono2())
                ->setDireccion($empleado->getDireccion())
                ->setCentroCosto($empleado->getCentroCosto())
                ->setConvenio($empleado->getConvenio())
                ->setFechaIngreso($empleado->getFechaIngreso())
                ->setFechaRetiro($empleado->getFechaRetiro())
                ->setCargo($empleado->getCargo());

            if(!$empleadoDb->getRepresentante() && $empleadoDb->getConvenio() && $empleadoDb->getConvenio()->hasEncargados()) {
                $empleadoDb->setRepresentante($empleado->getConvenio()->getEncargados()->first());
            }
            $updated = $empleadoDb;
        }
        return $updated;
    }

    private function insertEmpleadoDatabase(Empleado $empleado)
    {
        if($empleado->getConvenio() && $empleado->getConvenio()->hasEncargados()) {
            $empleado->setRepresentante($empleado->getConvenio()->getEncargados()->first());
        }
        $this->em->persist($empleado);
    }


    private function updateUsuario(Usuario $usuario)
    {
        $usuarioDb = $this->em->getRepository(Usuario::class)
            ->findOneBy(['identificacion' => $usuario->getIdentificacion()]);
        $updated = false;
        if($usuarioDb) {
            $usuarioDb
                ->setEmail($usuario->getEmail())
                ->setPrimerNombre($usuario->getPrimerNombre())
                ->setSegundoNombre($usuario->getSegundoNombre())
                ->setPrimerApellido($usuario->getPrimerApellido())
                ->setSegundoApellido($usuario->getSegundoApellido())
                ->addRol('ROLE_EMPLEADO');
            $updated = $usuarioDb;
        }
        return $updated;
    }

    private function insertUsuario(Usuario $usuario)
    {
        $pass = substr($usuario->getIdentificacion(), -4);
        $encodedPass = $this->passwordEncoder->encodePassword($usuario, $pass);
        $usuario
            ->setPassword($encodedPass)
            ->addRol('ROLE_EMPLEADO')
            ->aceptarTerminos();
        $this->em->persist($usuario);
        return $usuario;
    }
}