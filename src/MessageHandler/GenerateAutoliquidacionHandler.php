<?php


namespace App\MessageHandler;


use App\Message\Scraper\GenerateAutoliquidacion;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Service\Scraper\AutoliquidacionScraper;
use App\Service\Scraper\Exception\ScraperException;
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

    public function __construct(AutoliquidacionScraper $scraper,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepo,
                                EntityManagerInterface $em, ScraperMessenger $scraperMessenger)
    {
        $this->scraper = $scraper;
        $this->autoliquidacionEmpleadoRepo = $autoliquidacionEmpleadoRepo;
        $this->em = $em;
        $this->scraperMessenger = $scraperMessenger;
    }

    public function __invoke(GenerateAutoliquidacion $generateAutoliquidacion)
    {
        $autoliquidacion = $this->autoliquidacionEmpleadoRepo->find($generateAutoliquidacion->getAutoliquidacionEmpleadoId());
        try {
            $response = $this->scraper->generatePdf(
                $autoliquidacion->getUsuario()->getIdentificacion(),
                $autoliquidacion->getAutoliquidacion()->getPeriodo(),
                $generateAutoliquidacion->getPage()
            );

            $autoliquidacion->setExito(true)
                ->setCode($response->getCode())
                ->setSalida($response->getMessage());
        } catch(ScraperException $exception) {
            $autoliquidacion->setExito(false)
                ->setCode($exception->getStatusCode())
                ->setSalida($exception->getMessage());
        } catch (Exception $exception) {
            $autoliquidacion->setExito(false)
                ->setCode($exception->getCode())
                ->setSalida($exception->getMessage());
        }
        $this->em->flush();
        if($autoliquidacion->isExito()) {
            $this->scraperMessenger->downloadAutoliquidacion($autoliquidacion);
        }
    }
}