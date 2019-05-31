<?php


namespace App\Service\NovasoftImport;


use App\Entity\Empleado;
use App\Entity\Usuario;
use App\Repository\EmpleadoRepository;
use App\Repository\UsuarioRepository;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use App\Service\ReportesServicioEmpleados;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmpleadoImport
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ReportesServicioEmpleados
     */
    private $reportesServicioEmpleados;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    private $updateInsertIdents = [];
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(ReportesServicioEmpleados $reportesServicioEmpleados, EntityManagerInterface $em,
                                EmpleadoRepository $empleadoRepository, UsuarioRepository $usuarioRepository,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
        $this->empleadoRepository = $empleadoRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function import($convenios, $desde, $hasta)
    {
        $convenios = !is_array($convenios) ? [$convenios] : $convenios;
        foreach ($convenios as $convenio) {
            $empleados = $this->reportesServicioEmpleados->getEmpleados($convenio->getCodigo(), $desde, $hasta);
            foreach ($empleados as $empleado) {
                if(!$this->updateEmpleado($empleado)) {
                    $this->insertEmpleado($empleado);
                }
            }
        }
    }

    /**
     * Segun objeto empleado, si existe lo actualiza, si no lo inserta
     * @param Empleado $empleado
     * @throws \Doctrine\ORM\ORMException
     */
    public function insertEmpleado(Empleado $empleado)
    {
        $insertado = false;
        $pass = substr($empleado->getUsuario()->getIdentificacion(), -4);
        $encodedPass = $this->passwordEncoder->encodePassword($empleado->getUsuario(), $pass);
        $empleado->getUsuario()
            ->setPassword($encodedPass)
            ->setRoles(["ROLE_EMPLEADO"])
            ->aceptarTerminos();

        // en algunas ocaciones novasoft puede retornar un empleado repetido
        if (!in_array($empleado->getUsuario()->getIdentificacion(), $this->updateInsertIdents)) {
            $insertado = true;
            $this->em->persist($empleado);
        }

        $this->updateInsertIdents[] = $empleado->getUsuario()->getIdentificacion();
        return $insertado;
    }

    public function updateEmpleado(Empleado $empleado)
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

            $empleadoDb->getUsuario()
                ->setEmail($empleado->getUsuario()->getEmail())
                ->setPrimerNombre($empleado->getUsuario()->getPrimerNombre())
                ->setSegundoNombre($empleado->getUsuario()->getSegundoNombre())
                ->setPrimerApellido($empleado->getUsuario()->getPrimerApellido())
                ->setSegundoApellido($empleado->getUsuario()->getSegundoApellido());

            $updated = true;
        }
        return $updated;
    }
}