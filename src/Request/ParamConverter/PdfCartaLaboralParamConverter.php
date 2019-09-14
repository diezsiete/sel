<?php

namespace App\Request\ParamConverter;

use App\Service\Configuracion\Configuracion;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\Pdf\PdfCartaLaboralServilabor;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class PdfCartaLaboralParamConverter implements ParamConverterInterface
{
    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var ContainerInterface
     */
    private $locator;

    public function __construct(Configuracion $configuracion, ContainerInterface $locator)
    {
        $this->configuracion = $configuracion;
        $this->locator = $locator;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {

        $name = $configuration->getName();
        if($this->configuracion->getEmpresa(true) === 'servilabor') {
            $pdfCartaLaboral = $this->locator->get(PdfCartaLaboralServilabor::class);
        } else {
            $pdfCartaLaboral = $this->locator->get(PdfCartaLaboral::class);
        }
        $request->attributes->set($name, $pdfCartaLaboral);
    }


    public function supports(ParamConverter $configuration)
    {
        $className = $configuration->getClass();
        return $className !== null && $className === PdfCartaLaboral::class;
    }
}