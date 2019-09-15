<?php


namespace App\Request\ParamConverter;


use App\Service\Autoliquidacion\Export;
use App\Service\Autoliquidacion\ExportPdf;
use App\Service\Autoliquidacion\ExportZip;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AutoliquidacionExportParamConverter implements ParamConverterInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $type = $request->attributes->get('type');
        switch($type) {
            case 'zip':
                $export = $this->container->get(ExportZip::class);
                break;
            case 'pdf':
                $export = $this->container->get(ExportPdf::class);
                break;
            default:
                throw new NotFoundHttpException();
        }
        $request->attributes->set($configuration->getName(), $export);
    }


    public function supports(ParamConverter $configuration)
    {
        $className = $configuration->getClass();
        return $className !== null && $className === Export::class;
    }
}