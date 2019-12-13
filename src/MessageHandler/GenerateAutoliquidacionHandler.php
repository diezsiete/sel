<?php


namespace App\MessageHandler;


use App\Message\Scraper\GenerateAutoliquidacion;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionProgresoRepository;
use App\Service\Scraper\AutoliquidacionScraper;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\ScraperMessenger;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GenerateAutoliquidacionHandler implements MessageHandlerInterface
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
     * @var ScraperMessenger
     */
    private $scraperMessenger;
    /**
     * @var AutoliquidacionProgresoRepository
     */
    private $autoliquidacionProgresoRepository;

    public function __construct(AutoliquidacionScraper $scraper,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepo,
                                AutoliquidacionProgresoRepository $autoliquidacionProgresoRepository,
                                EntityManagerInterface $em, ScraperMessenger $scraperMessenger)
    {
        $this->scraper = $scraper;
        $this->autoliquidacionEmpleadoRepo = $autoliquidacionEmpleadoRepo;
        $this->em = $em;
        $this->scraperMessenger = $scraperMessenger;
        $this->autoliquidacionProgresoRepository = $autoliquidacionProgresoRepository;
    }

    public function __invoke(GenerateAutoliquidacion $generateAutoliquidacion)
    {
        $autoliquidacionEmpleado = $this->autoliquidacionEmpleadoRepo->find($generateAutoliquidacion->getAutoliquidacionEmpleadoId());
        $autoliquidacion = $autoliquidacionEmpleado->getAutoliquidacion();
        $autoliquidacionProgreso = $this->autoliquidacionProgresoRepository->find($generateAutoliquidacion->getProgresoId());

        try {
            $response = $this->scraper->generatePdf(
                $autoliquidacionEmpleado->getUsuario()->getIdentificacion(),
                $autoliquidacionEmpleado->getAutoliquidacion()->getPeriodo(),
                $generateAutoliquidacion->getPage()
            );

            $message = $response->getMessage();

            $autoliquidacionEmpleado->setExito(true)
                ->setCode($response->getCode())
                ->setSalida($message);

            $autoliquidacionProgreso->setLastMessage($message);

        } catch(ScraperException $exception) {
            $exito = $exception instanceof ScraperNotFoundException;
            $autoliquidacionEmpleado->setExito($exito)
                ->setCode($exception->getStatusCode())
                ->setSalida($exception->getMessage());

            $autoliquidacion->calcularPorcentajeEjecucion();
            $autoliquidacionProgreso->addCount()->calcPorcentaje();
        } catch (Exception $exception) {
            $autoliquidacionEmpleado->setExito(false)
                ->setCode($exception->getCode())
                ->setSalida($exception->getMessage());

            $autoliquidacion->calcularPorcentajeEjecucion();
            $autoliquidacionProgreso->addCount()->calcPorcentaje();
        }

        $this->em->flush();
        if($autoliquidacionEmpleado->isExito()) {
            $this->scraperMessenger->downloadAutoliquidacion($autoliquidacionEmpleado, $autoliquidacionProgreso);
        }
    }
}