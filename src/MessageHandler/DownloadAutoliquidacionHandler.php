<?php


namespace App\MessageHandler;


use App\Message\Scraper\DownloadAutoliquidacion;
use App\Message\Scraper\GenerateAutoliquidacion;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
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

    public function __construct(AutoliquidacionScraper $scraper,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepo,
                                EntityManagerInterface $em, FileManager $autoliquidacionService)
    {
        $this->scraper = $scraper;
        $this->autoliquidacionEmpleadoRepo = $autoliquidacionEmpleadoRepo;
        $this->em = $em;
        $this->autoliquidacionService = $autoliquidacionService;
    }

    public function __invoke(DownloadAutoliquidacion $downloadAutoliquidacion)
    {
        $autoliquidacion = $this->autoliquidacionEmpleadoRepo->find($downloadAutoliquidacion->getAutoliquidacionEmpleadoId());
        $ident = $autoliquidacion->getUsuario()->getIdentificacion();
        $periodo = $autoliquidacion->getAutoliquidacion()->getPeriodo();

        try {
            $resource = $this->scraper->downloadPdf($ident, $periodo);
            if($resource) {
                $this->autoliquidacionService->uploadPdfResource($periodo, $ident, $resource);
                $autoliquidacion
                    ->setExito(true)
                    ->setSalida($autoliquidacion->getSalida() . ". \nArchivo descargdo exitosamente");
            } else {
                throw new Exception("resource is false");
            }
        }
        catch (Exception $exception) {
            $autoliquidacion->setExito(false)
                ->setCode($exception->getCode())
                ->setSalida($autoliquidacion->getSalida() . ". \nError descarga archivo");
        }
        $this->em->flush();
        if($autoliquidacion->isExito()) {
            $this->scraper->deletePdf($ident, $periodo);
        }
    }
}