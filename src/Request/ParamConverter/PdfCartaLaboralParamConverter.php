<?php

namespace App\Request\ParamConverter;

use App\Repository\ConvenioRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\Pdf\PdfCartaLaboralServilabor;
use App\Service\Pdf\PdfCartaLaboralUniversal;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

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
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;

    public function __construct(Configuracion $configuracion, ContainerInterface $locator, Security $security, ConvenioRepository $convenioRepository)
    {
        $this->configuracion = $configuracion;
        $this->locator = $locator;
        $this->security = $security;
        $this->convenioRepository = $convenioRepository;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        if($this->configuracion->getEmpresa(true) === 'servilabor') {
            $pdfCartaLaboral = $this->locator->get(PdfCartaLaboralServilabor::class);
            if($convenio = $this->convenioRepository->findConvenioByUser($this->security->getUser())) {
                $pdfCartaLaboral->setCompania($convenio->getSsrsDb());
            }
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