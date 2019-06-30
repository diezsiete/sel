<?php

namespace App\Command\NovasoftImport;

use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\Usuario;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use App\Repository\UsuarioRepository;
use App\Service\Configuracion\SsrsDb;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class NiEmpleadoCommand extends PeriodoCommand
{
    protected static $defaultName = 'ni:empleado';
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;


    public function __construct(ConvenioRepository $convenioRepository, EmpleadoRepository $empleadoRepository,
                                UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->convenioRepository = $convenioRepository;
        $this->empleadoRepository = $empleadoRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->usuarioRepository = $usuarioRepository;
    }

    protected function configure()
    {
        $this->setDescription('Actualizar empleados desde novasoft')
            ->addArgument('convenios', InputArgument::IS_ARRAY,
                'convenios codigos para descargar. Omita y se toman todos' );
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $desde = $this->getInicio($input);
        $hasta = $this->getFin($input);

        $dbs = $this->getSsrsDbs();

        foreach($dbs as $db) {
            if($db->hasConvenios()) {
                foreach ($this->getConvenios($db) as $convenio) {
                    $this->io->writeln($convenio->getCodigo());
                    $empleados = $this->reportesServicioEmpleados->setSsrsDb($db)->getEmpleados($convenio->getCodigo(), $desde, $hasta);
                    foreach ($empleados as $empleado) {
                        $this->importEmpleado($empleado, $db);
                        $this->em->flush();
                    }
                }
            } else {
                $empleados = $this->reportesServicioEmpleados->setSsrsDb($db)->getEmpleado();
                foreach($empleados as $empleado) {
                    $this->importEmpleado($empleado, $db);
                    $this->em->flush();
                }
            }
        }
    }

    private function importEmpleado(Empleado $empleado, SsrsDb $ssrsDb)
    {
        $usuario = $this->updateUsuario($empleado->getUsuario());
        $usuarioMessage = "[usuario update]";
        if (!$usuario) {
            $usuario = $this->insertUsuario($empleado->getUsuario());
            $usuarioMessage = "[usuario insert]";
        }
        $empleado->setUsuario($usuario);
        $empleado->setSsrsDb($ssrsDb->getNombre());

        $empleadoMessage = "[empleado update]";
        if (!$this->updateEmpleado($empleado)) {
            $this->em->persist($empleado);
            $empleadoMessage = "[empleado insert]";
        }
        $this->io->writeln(sprintf("    %s %s %s %s",
            $empleado->getUsuario()->getNombreCompleto(), $empleado->getUsuario()->getIdentificacion(), $usuarioMessage, $empleadoMessage));
    }

    /**
     * @return Convenio[]
     */
    private function getConvenios(SsrsDb $ssrsDb)
    {
        $conveniosCodigos = $this->input->getArgument('convenios');
        return $conveniosCodigos ?
            $this->convenioRepository->findBy(['codigo' => $conveniosCodigos, 'ssrsDb' => $ssrsDb->getNombre()]) :
            $this->convenioRepository->findBy(['ssrsDb' => $ssrsDb->getNombre()]);
    }

    private function updateEmpleado(Empleado $empleado)
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

            $updated = true;
        }
        return $updated;
    }


    private function updateUsuario(Usuario $usuario)
    {
        $usuarioDb = $this->usuarioRepository->findOneBy(['identificacion' => $usuario->getIdentificacion()]);
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
