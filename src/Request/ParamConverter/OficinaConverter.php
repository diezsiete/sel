<?php


namespace App\Request\ParamConverter;


use App\Service\Configuracion\Configuracion;
use App\Service\Configuracion\Oficina;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OficinaConverter implements ParamConverterInterface
{
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $oficinaNombre = $request->attributes->get($name);
        $object = $this->configuracion->oficinas($oficinaNombre);
        if(!$object) {
            throw new NotFoundHttpException("Oficina '$oficinaNombre' no existe");
        }
        $request->attributes->set($name, $object);
    }


    public function supports(ParamConverter $configuration)
    {
        $className = $configuration->getClass();
        return $className !== null && $className === Oficina::class;
    }
}