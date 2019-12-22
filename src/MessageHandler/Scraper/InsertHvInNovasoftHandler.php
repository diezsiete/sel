<?php


namespace App\MessageHandler\Scraper;

use App\Message\Scraper\InsertHvInNovasoft;
use App\Message\Scraper\UpdateHvDatosBasicosInNovasoft;
use App\Service\Scraper\HvNovasoftHandler;
use App\Service\Scraper\NovasoftScraper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class InsertHvInNovasoftHandler implements MessageHandlerInterface
{
    use HvNovasoftHandler;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;
    /**
     * @var NovasoftScraper
     */
    private $scraper;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    public function __construct(EntityManagerInterface $em, NormalizerInterface $normalizer,
                                EventDispatcherInterface $eventDispatcher, NovasoftScraper $scraper,
                                LoggerInterface $messengerAuditLogger)
    {
        $this->em = $em;
        $this->normalizer = $normalizer;
        $this->logger = $messengerAuditLogger;
        $this->scraper = $scraper;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(InsertHvInNovasoft $message)
    {
        try {
            $this->em->clear();
            $solicitud = $this->getSolicitud($this->em, $message->getSolicitudId(), $this->logger);

            $data = $this->normailizeFullHv($solicitud->getHv(), $this->normalizer);

            $this->subscribeToStreamResponse($this->eventDispatcher);

            $response = $this->scraper->postHv($data);

            $this->currentSolicitud->addLog($response->getMessage());
        }catch (Exception $e) {
            $this->currentSolicitud->addLog($e->getMessage());
            throw $e;
        }
        finally {
            $this->em->flush();
            $this->unsubscribeToStreamResponse($this->eventDispatcher);
        }
    }
}