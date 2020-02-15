<?php


namespace App\Service\Novasoft\Api\Importer;


use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use App\Repository\Main\ConvenioRepository;
use App\Repository\Main\EmpleadoRepository;
use App\Repository\Main\UsuarioRepository;
use App\Service\Novasoft\Api\Client\EmpleadoClient;
use App\Service\Novasoft\Api\Client\NovasoftApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EmpleadoImporter
{
    /**
     * @var NovasoftApiClient
     */
    private $client;
    /**
     * @var ConvenioRepository
     */
    private $convenioRepo;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepo;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepo;

    public function __construct(EmpleadoClient $client, DenormalizerInterface $denormalizer,
                                EmpleadoRepository $empleadoRepo, ConvenioRepository $convenioRepo,
                                UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
        $this->empleadoRepo = $empleadoRepo;
        $this->convenioRepo = $convenioRepo;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    /**
     * @param $identificacion
     * @return Empleado|null
     * @throws ExceptionInterface
     */
    public function import($identificacion)
    {
        $response = $this->client->get($identificacion);
        /** @var Empleado $empleadoNapi */
        $empleadoNapi = $this->denormalizer->denormalize($response, Empleado::class);

        $convenio = null;
        $persist = false;
        if($convenioNapi = $empleadoNapi->getConvenio()) {
            $convenio = $this->convenioRepo->find($convenioNapi->getCodigo());
            if(!$convenio) {
                $convenio = $convenioNapi;
                $persist = true;
            }
        }

        $empleado = $this->empleadoRepo->findByIdentificacion($empleadoNapi->getUsuario()->getIdentificacion());
        if($empleado) {
            $empleado = $this->updateEmpleado($empleado, $empleadoNapi, $convenio);
        } else {
            $empleado = $this->createEmpleado($empleadoNapi, $convenio);
            $persist = (bool)$empleado;
        }

        if($persist) {
            $this->em->persist($empleado);
        }
        $this->em->flush();

        return $empleado;
    }

    private function updateEmpleado(Empleado $empleado, Empleado $empleadoNapi, ?Convenio $convenio = null)
    {
        $empleado
            ->setSexo($empleadoNapi->getSexo())
            ->setEstadoCivil($empleadoNapi->getEstadoCivil())
            ->setNacimiento($empleadoNapi->getNacimiento())
            ->setFechaIngreso($empleadoNapi->getFechaIngreso())
            ->setFechaRetiro($empleadoNapi->getFechaRetiro())
            ->setConvenio($convenio);

        if($usuarioNapi = $empleadoNapi->getUsuario()) {
            $empleado->getUsuario()
                ->setPrimerApellido($usuarioNapi->getPrimerApellido())
                ->setSegundoApellido($usuarioNapi->getSegundoApellido())
                ->setPrimerNombre($usuarioNapi->getPrimerNombre())
                ->setSegundoNombre($usuarioNapi->getSegundoNombre())
                ->setEmail($usuarioNapi->getEmail())
                ->addRol('ROLE_EMPLEADO');
        }
        return $empleado;
    }

    private function createEmpleado(Empleado $empleadoNapi, ?Convenio $convenio = null): ?Empleado
    {
        $empleado = null;
        if($usuarioNapi = $empleadoNapi->getUsuario()) {
            $pass = substr($usuarioNapi->getIdentificacion(), -4);
            $encodedPass = $this->passwordEncoder->encodePassword($usuarioNapi, $pass);
            $usuarioNapi
                ->setPassword($encodedPass)
                ->addRol('ROLE_EMPLEADO')
                ->aceptarTerminos();
            $empleadoNapi->setConvenio($convenio);
            $empleado = $empleadoNapi;
        }
        return $empleado;
    }


}