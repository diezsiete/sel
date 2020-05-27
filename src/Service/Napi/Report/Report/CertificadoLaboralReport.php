<?php


namespace App\Service\Napi\Report\Report;


use App\Entity\Main\Usuario;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoLaboral;
use App\Service\Configuracion\Configuracion;
use App\Service\Napi\Report\LocalPdf;
use App\Service\Napi\Report\SsrsReport;
use App\Service\Napi\Client\NapiClient;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\Pdf\PdfCartaLaboralServilabor;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class CertificadoLaboralReport extends SsrsReport implements ServiceSubscriberInterface
{
    use LocalPdf;

    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(NapiClient $client, EntityManagerInterface $em, EventDispatcherInterface $dispatcher,
                                Configuracion $configuracion, ContainerInterface $container)
    {
        parent::__construct($client, $em, $dispatcher, $configuracion);
        $this->configuracion = $configuracion;
        $this->container = $container;
    }

    protected function callOperation(Usuario $usuario)
    {
        return $this->client->itemOperations(CertificadoLaboral::class)->get($usuario->getIdentificacion());
    }

    public function renderPdf()
    {
        $object = $this->callOperation($this->currentReport->getUsuario());
        $pdfService = $this->container->get($this->configuracion->getEmpresa(true) === 'servilabor'
            ? PdfCartaLaboralServilabor::class : PdfCartaLaboral::class);

        //TODO esta parte creo que tiene que ver si no es compania default (ej: Universal)
//        if($ssrsDb = $this->container->get(NovasoftEmpleadoService::class)->getSsrsDb(trim($map[0]->getCedula()))) {
//            $pdfService->setCompania($ssrsDb);
//        }
        return $pdfService->render($object)->Output('S');
    }

    /**
     * @return string
     */
    public function getPdfFileName(): string
    {
        return 'napi/se/certificado-laboral/' . $this->currentReport->getNumeroIdentificacion(). '.pdf';
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            PdfCartaLaboral::class,
            PdfCartaLaboralServilabor::class,
            NovasoftEmpleadoService::class
        ];
    }
}