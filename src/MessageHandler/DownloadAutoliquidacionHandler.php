<?php


namespace App\MessageHandler;


use App\Message\Scraper\DownloadAutoliquidacion;
use App\Message\Scraper\GenerateAutoliquidacion;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionProgresoRepository;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Scraper\AutoliquidacionScraper;
use App\Service\Scraper\Exception\ScraperException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DownloadAutoliquidacionHandler implements MessageHandlerInterface
{
    /**
     * @var AutoliquidacionScraper
     */
    private $scraper;
    /**
     * @var AutoliquidacionEmpleadoRepository
     */
    private $autoliquidacionEmpleadoRepo;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var FileManager
     */
    private $autoliquidacionService;
    /**
     * @var AutoliquidacionProgresoRepository
     */
    private $autoliquidacionProgresoRepository;

    public function __construct(AutoliquidacionScraper $scraper,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepo,
                                AutoliquidacionProgresoRepository $autoliquidacionProgresoRepository,
                                EntityManagerInterface $em, FileManager $autoliquidacionService)
    {
        $this->scraper = $scraper;
        $this->autoliquidacionEmpleadoRepo = $autoliquidacionEmpleadoRepo;
        $this->em = $em;
        $this->autoliquidacionService = $autoliquidacionService;
        $this->autoliquidacionProgresoRepository = $autoliquidacionProgresoRepository;
    }

    public function __invoke(DownloadAutoliquidacion $downloadAutoliquidacion)
    {
        $autoliquidacionEmpleado = $this->autoliquidacionEmpleadoRepo->find($downloadAutoliquidacion->getAutoliquidacionEmpleadoId());
        $autoliquidacion = $autoliquidacionEmpleado->getAutoliquidacion();
        $autoliquidacionProgreso = $this->autoliquidacionProgresoRepository->find($downloadAutoliquidacion->getAutoliquidacionProgresoId());

        $ident = $autoliquidacionEmpleado->getUsuario()->getIdentificacion();
        $periodo = $autoliquidacionEmpleado->getAutoliquidacion()->getPeriodo();

        try {
            $resource = $this->scraper->downloadPdf($ident, $periodo);
            if($resource) {
                $this->autoliquidacionService->uploadPdfResource($periodo, $ident, $resource);
                $autoliquidacionEmpleado
                    ->setExito(true)
                    ->setSalida($autoliquidacionEmpleado->getSalida() . ". \nArchivo descargdo exitosamente");

                $autoliquidacion->calcularPorcentajeEjecucion();
                $autoliquidacionProgreso->addCount()->calcPorcentaje();
            } else {
                throw new Exception("resource is false");
            }
        }
        catch (Exception $exception) {
            $autoliquidacionEmpleado->setExito(false)
                ->setCode($exception->getCode())
                ->setSalida($autoliquidacionEmpleado->getSalida() . ". \nError descarga archivo");

            $autoliquidacion->calcularPorcentajeEjecucion();
            $autoliquidacionProgreso->addCount()->calcPorcentaje();
        }

        $this->em->flush();

        if($autoliquidacionEmpleado->isExito()) {
            $this->scraper->deletePdf($ident, $periodo);
        }
    }
}