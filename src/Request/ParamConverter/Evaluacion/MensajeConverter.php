<?php


namespace App\Request\ParamConverter\Evaluacion;


use App\Service\Evaluacion\Mensaje;
use App\Service\Evaluacion\Navegador;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class MensajeConverter implements ParamConverterInterface
{

    /**
     * Stores the object in the request.
     *
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        foreach($request->attributes->all() as $attribute) {
            if($attribute instanceof Navegador) {
                $mensaje = new Mensaje($attribute, $attribute->getEvaluador());
                $request->attributes->set($configuration->getName(), $mensaje);
            }
        }
    }

    /**
     * Checks if the object is supported.
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        $className = $configuration->getClass();
        $supports =  $className !== null && $className === Mensaje::class;
        return $supports;
    }
}