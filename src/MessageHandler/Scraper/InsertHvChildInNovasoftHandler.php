<?php


namespace App\MessageHandler\Scraper;

use App\Entity\HvEntity;
use App\Message\Scraper\InsertHvChildInNovasoft;
use App\Message\Scraper\InsertHvInNovasoft;
use App\Message\Scraper\UpdateHvDatosBasicosInNovasoft;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\HvNovasoftHandler;
use App\Service\Scraper\NovasoftScraper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class InsertHvChildInNovasoftHandler implements MessageHandlerInterface
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

    public function __invoke(InsertHvChildInNovasoft $message)
    {
        $this->em->clear();
        try {
            $this->getSolicitud($this->em, $message->getSolicitudId(), $this->logger);

            /** @var HvEntity $hvEntity */
            $hvEntity = $this->em->getRepository($message->getChildClass())->find($message->getChildId());

            $data = $this->normalizeOneHvChild($hvEntity, $this->normalizer);

            $this->subscribeToStreamResponse($this->eventDispatcher);

            $this->logger->debug("inserting child[{$message->getChildClass()}] with id='{$message->getChildId()}'", ['data' => $data]);

            $response = $this->scraper->insertChild($data);

            $this->currentSolicitud->addLog($response->getMessage());
        }
        catch (ScraperNotFoundException $e) {
            $this->logger->warning("Scrapper not found exception ({$e->getMessage()}), trying insert hv...", [
                'childClass' => $message->getChildClass(),
                'childId' => $message->getChildId(),
                'solicitudId' => $message->getSolicitudId(),
            ]);
            $data = $this->normailizeFullHv($this->currentSolicitud->getHv(), $this->normalizer);
            $response = $this->scraper->postHv($data);
            $this->currentSolicitud->addLog($response->getMessage());
        }
        catch (Exception $e) {
            $this->currentSolicitud->addLog($e->getMessage());
            throw $e;
        }
        finally {
            $this->em->flush();
            $this->unsubscribeToStreamResponse($this->eventDispatcher);
        }
    }
}