<?php


namespace App\Service\Novasoft\Api\Importer;


use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use App\Repository\Main\ConvenioRepository;
use App\Service\Novasoft\Api\Client\EmpleadoClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EmpleadoImporter
{
    /**
     * @var \App\Service\Novasoft\Api\Client\NovasoftApiClient
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

    public function __construct(EmpleadoClient $client, DenormalizerInterface $denormalizer, ConvenioRepository $convenioRepo,
                                UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
        $this->convenioRepo = $convenioRepo;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    /**
     * @param $identificacion
     * @return Empleado|null
     */
    public function import($identificacion)
    {
        $response = $this->client->get($identificacion);
        dump($response);
        $empleado = $this->denormalizer->denormalize($response, Empleado::class);
        dump($empleado);

        //TODO por ahora se hace manual la conversion, la idea es que sea automatica con serializer
//        if($empleadoSqlsrv) {
//            $empleado = (new Empleado())
//                ->setSexo($empleadoSqlsrv['sexo'])
//                ->setEstadoCivil($empleadoSqlsrv['estadoCivil']);
//
//            if($empleadoConvenio = $this->fetchEmpleadoConvenio($empleadoSqlsrv)) {
//                $fechaIngreso = DateTime::createFromFormat('Y-m-d', $empleadoConvenio['fechaIngreso']);
//                $fechaRetiro = $empleadoConvenio['fechaRetiro']
//                    ? DateTime::createFromFormat('Y-m-d', $empleadoConvenio['fechaRetiro']) : null;
//
//                $empleado
//                    ->setFechaIngreso($fechaIngreso)
//                    ->setFechaRetiro($fechaRetiro);
//
//                if($convenio = $this->convenioRepo->find($empleadoConvenio['codigoConvenio'])) {
//                    $empleado->setConvenio($convenio);
//                }
//            }
//
//            $usuario = $this->createUsuario(
//                $empleadoSqlsrv['identificacion'], $empleadoSqlsrv['primerNombre'], $empleadoSqlsrv['segundoNombre'],
//                $empleadoSqlsrv['primerApellido'], $empleadoSqlsrv['segundoApellido']);
//
//            $empleado->setUsuario($usuario);
//
//            $this->em->persist($empleado);
//            $this->em->flush();
//        } else {
//            $empleado = null;
//        }
//
//        return $empleado;
    }


    private function createUsuario($ident, $nom1, $nom2, $apellido1, $apellido2)
    {
        $usuario = (new Usuario())
            //TODO todavia no se si usar identificacion o codigo, (extranjeros)
            ->setIdentificacion($ident)
            ->setPrimerNombre($nom1)
            ->setSegundoNombre($nom2)
            ->setPrimerApellido($apellido1)
            ->setSegundoApellido($apellido2);

        $pass = substr($usuario->getIdentificacion(), -4);
        $encodedPass = $this->passwordEncoder->encodePassword($usuario, $pass);
        $usuario
            ->setPassword($encodedPass)
            ->addRol('ROLE_EMPLEADO')
            ->aceptarTerminos();

        return $usuario;
    }

    private function fetchEmpleadoConvenio($data)
    {
        $empleadoConvenio = null;
        if($empleadoConvenios = $data['empleadoConvenios']) {
            if (count($empleadoConvenios) === 1) {
                $empleadoConvenio = $empleadoConvenios[0];
            } else {
                $sinFechaRetiro = [];
                foreach ($empleadoConvenios as $empleadoConvenio) {
                    if ($empleadoConvenio['fechaRetiro'] === null) {
                        $sinFechaRetiro[] = $empleadoConvenio;
                    }
                }
                if ($sinFechaRetiro) {
                    $empleadoConvenio = $sinFechaRetiro[count($sinFechaRetiro) - 1];
                } else {
                    $empleadoConvenio = $empleadoConvenios[count($empleadoConvenios) - 1];
                }
            }
        }
        return $empleadoConvenio;
    }
}