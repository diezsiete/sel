<?php


namespace App\MessageHandler;


use App\Entity\HvEntity;
use App\Message\UploadToNovasoft;
use App\Message\UploadToNovasoftSuccess;
use App\Messenger\Transport\Scraper\ScraperTransport;
use App\Repository\HvRepository;
use App\Service\Scraper\HvScraper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class UploadToNovasoftHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var HvScraper
     */
    private $scraper;
    /**
     * @var HvRepository
     */
    private $hvRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ScraperTransport
     */
    private $transportScraper;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(HvScraper $scraper, HvRepository $hvRepository, EntityManagerInterface $em,
                                TransportInterface $transportScraper, MessageBusInterface $messageBus)
    {
        $this->scraper = $scraper;
        $this->hvRepository = $hvRepository;
        $this->em = $em;
        $this->transportScraper = $transportScraper;
        $this->messageBus = $messageBus;
    }

    public function __invoke(UploadToNovasoft $uploadToNovasoft)
    {
        $this->em->clear();
        $hvId = $uploadToNovasoft->getHvId();

        if ($this->transportScraper->hvIdHasFailed($hvId)) {
            throw new UnrecoverableMessageHandlingException("Hv id '$hvId' tiene un comando fallido en cola");
        }

        $hv = $this->hvRepository->find($hvId);
        if(!$hv) {
            // exception retry
            // return removed
            if($this->logger) {
                $this->logger->error(sprintf('Hv %s no encontrada', $hvId));
            }
            return;
        }
        if(!$uploadToNovasoft->getChildId() && !$uploadToNovasoft->getAction() !== UploadToNovasoft::ACTION_CHILD_DELETE) {
            $response = $uploadToNovasoft->getAction() === UploadToNovasoft::ACTION_INSERT ?
                $this->scraper->postHv($hv) : $this->scraper->putHv($hv);
        } else {
            if($childId = $uploadToNovasoft->getChildId()) {
                /** @var HvEntity $hvChild */
                $hvChild = $this->em->getRepository($uploadToNovasoft->getChildClass())->find($childId);
                if ($uploadToNovasoft->getAction() === UploadToNovasoft::ACTION_CHILD_INSERT) {

                    $response = $this->scraper->insertChild($hvChild);
                    dump($response);
                } else {
                    dump("UPDATE");
                    $this->scraper->updateChild($hvChild);
                }
            } else {
                dump("DELETE");
                $this->scraper->deleteChild($hv, $uploadToNovasoft->getChildClass());
            }
        }

        $this->messageBus->dispatch(new UploadToNovasoftSuccess(
            $hvId, $uploadToNovasoft->getChildId(), $uploadToNovasoft->getChildClass(), $uploadToNovasoft->getAction())
        );
    }
}