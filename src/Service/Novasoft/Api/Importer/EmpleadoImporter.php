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
     * @return EmpleadoClient
     */
    public function getClient(): EmpleadoClient
    {
        return $this->client;
    }

    /**
     * @param $identificacion
     * @return Empleado|null
     */
    public function getNapiEmpleado($identificacion): ?Empleado
    {
        return $this->client->get($identificacion);
    }
    /**
     * @param string|Empleado|null $empleado string identificacion, Empleado asume el objeto retornado por napi, null hace nada
     * @return Empleado|null
     */
    public function importEmpleado($empleado): ?Empleado
    {
        if($empleado) {
            $empleado = $empleado instanceof Empleado ? $empleado : $this->getNapiEmpleado($empleado);
            if ($empleado) {
                $empleado->getUsuario()->addRol('ROLE_EMPLEADO');
                if (!$empleado->getId()) {
                    $empleado = $this->createEmpleado($empleado);
                    $this->em->persist($empleado);
                }
                $this->em->flush();
            }
        }
        return $empleado;
    }

    private function createEmpleado(Empleado $empleadoNapi): ?Empleado
    {
        $empleado = null;
        if($usuarioNapi = $empleadoNapi->getUsuario()) {
            $pass = substr($usuarioNapi->getIdentificacion(), -4);
            $encodedPass = $this->passwordEncoder->encodePassword($usuarioNapi, $pass);
            $usuarioNapi
                ->setPassword($encodedPass)
                ->addRol('ROLE_EMPLEADO')
                ->aceptarTerminos();
            $empleado = $empleadoNapi;
        }
        return $empleado;
    }


}