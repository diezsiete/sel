<?php

namespace App\Command\NovasoftImport;

use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\RangoPeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\Usuario;
use App\Service\ReportesServicioEmpleados;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class NiEmpleadoCommand
 * @package App\Command\NovasoftImport
 */
class NiEmpleadoCommand extends TraitableCommand
{
    use Loggable,
        PeriodoOption,
        RangoPeriodoOption,
        SearchByConvenioOrEmpleado,
        SelCommandTrait;

    protected static $defaultName = 'sel:ni:empleado';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ReportesServicioEmpleados
     */
    private $reportesServicioEmpleados;


    public function __construct(Reader $reader, EventDispatcherInterface $dispatcher,
                                ReportesServicioEmpleados $reportesServicioEmpleados,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($reader, $dispatcher);
        $this->passwordEncoder = $passwordEncoder;
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
    }

    protected function configure()
    {
        $this->setDescription('Actualizar empleados desde novasoft')
            ->addOption('start_from', null, InputOption::VALUE_OPTIONAL,
                'Codigo convenio desde donde se empieza la importacion. Ignora argumento search')
        ;
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $desde = $this->getInicio($input);
        $hasta = $this->getFin($input);



        if($this->isSearchConvenio()) {
            foreach ($this->getConvenios() as $convenio) {
                $codigo = $convenio->getCodigo();
                $ssrsDb = $convenio->getSsrsDb();

                $empleados = $this->reportesServicioEmpleados->setSsrsDb($ssrsDb)->getEmpleados($codigo, $desde, $hasta);
                foreach ($empleados as $empleado) {
                    //empleados provenientes de nom933 no tienen convenio
                    if(!$empleado->getConvenio()) {
                        $empleado->setConvenio($convenio);
                    }
                    $this->importEmpleado($empleado, $ssrsDb);
                    // movemos flush aca dado que existe el caso que un convenio traiga empleados repetidos (PTASAS0001: 52985971)
                    $this->em->flush();
                }
                //$this->em->flush();
            }
        } else {
            foreach($this->getEmpleados() as $empleado) {
                $ssrsDb = $empleado->getSsrsDb();
                $ident = $empleado->getUsuario()->getIdentificacion();
                $empleadoNovasoft = $this->reportesServicioEmpleados->setSsrsDb($ssrsDb)->getEmpleado($ident);
                if($empleadoNovasoft) {
                    $this->importEmpleado($empleadoNovasoft[0], $ssrsDb);
                }
            }
            $this->em->flush();
            $this->em->clear();
        }
    }

    private function importEmpleado(Empleado $empleado, string $ssrsDb)
    {
        $usuario = $this->updateUsuario($empleado->getUsuario());
        $usuarioMessage = "[usuario update]";
        if (!$usuario) {
            $usuario = $this->insertUsuario($empleado->getUsuario());
            $usuarioMessage = "[usuario insert]";
        }
        $empleado->setUsuario($usuario);
        $empleado->setSsrsDb($ssrsDb);

        $empleadoMessage = "[empleado update]";
        if (!$this->updateEmpleado($empleado)) {
            $this->insertEmpleado($empleado);
            $empleadoMessage = "[empleado insert]";
        }
        $this->info(sprintf("%s %s %s %s %s", $empleado->getConvenio()->getCodigo(),
            $empleado->getUsuario()->getNombreCompleto(), $empleado->getUsuario()->getIdentificacion(), $usuarioMessage, $empleadoMessage));
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

            if(!$empleadoDb->getRepresentante() && $empleadoDb->getConvenio()->hasEncargados()) {
                $empleadoDb->setRepresentante($empleado->getConvenio()->getEncargados()->first());
            }
            $updated = true;
        }
        return $updated;
    }

    private function insertEmpleado(Empleado $empleado)
    {
        if($empleado->getConvenio()->hasEncargados()) {
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
