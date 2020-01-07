<?php


namespace App\Request\ParamConverter;


use App\Entity\Main\Representante;
use App\Entity\Main\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


class NuevoRepresentanteConverter implements ParamConverterInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(EntityManagerInterface $em, FlashBagInterface $flashBag)
    {
        $this->em = $em;
        $this->flashBag = $flashBag;
    }

    /**
     * Stores the object in the request.
     *
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $convenio = $request->attributes->get('convenio');
        $name = $configuration->getName();
        $identifier = $request->attributes->get($name);
        $object = null;

        if($identifier) {
            $usuario = $this->em->getRepository(Usuario::class)->findByIdentificacion($identifier);

            if ($usuario) {
                $representante = $this->em->getRepository(Representante::class)
                    ->findBy(['convenio' => $convenio, 'usuario' => $usuario]);
                if (!$representante) {
                    $this->flashBag->clear();
                    $this->flashBag->add('success', 'Usuario ya registrado en el sistema.');
                } else {
                    $this->flashBag->clear();
                    $this->flashBag->add('danger', 'El usuario ya esta asignado a este convenio');
                    $usuario = null;
                }
            } else {
                $this->flashBag->clear();
                $this->flashBag->add('info', 'Usuario no existe en el sistema. Por favor ingrese los datos');
                $usuario = (new Usuario())->setIdentificacion($identifier);
            }

            if ($usuario) {
                $object = (new Representante())
                    ->setUsuario($usuario)
                    ->setConvenio($convenio);
            }
        }

        $request->attributes->set($name, $object);
        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        $className = $configuration->getClass();
        return $className !== null && $className === Representante::class && $configuration->getName() === 'nuevoRepresentante';
    }
}