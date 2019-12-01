<?php


namespace App\MessageHandler;

use App\Message\Scraper\UpdateHvInNovasoft;
use App\Repository\HvRepository;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\HvScraper;
use App\Service\Scraper\ScraperMessenger;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateHvInNovasoftHandler implements MessageHandlerInterface
{
    /**
     * @var HvScraper
     */
    private $scraper;
    /**
     * @var ScraperMessenger
     */
    private $scraperMessenger;
    /**
     * @var HvRepository
     */
    private $hvRepository;

    public function __construct(HvScraper $scraper, ScraperMessenger $scraperMessenger, HvRepository $hvRepository)
    {
        $this->scraper = $scraper;
        $this->scraperMessenger = $scraperMessenger;
        $this->hvRepository = $hvRepository;
    }

    public function __invoke(UpdateHvInNovasoft $updateHvInNovasoft)
    {
        $data = $updateHvInNovasoft->getHvData();

        if ($updateHvInNovasoft->isUpdate()) {
            $response = $this->scraper->putHv($data);
        }
        elseif ($updateHvInNovasoft->isChildInsert()) {
            try {
                $response = $this->scraper->insertChild($data);
            } catch (ScraperNotFoundException $e) {
                $this->scraperMessenger->insertToNovasoft($this->hvRepository->find($updateHvInNovasoft->getHvId()));
                throw new UnrecoverableMessageHandlingException();
            }
        }
        elseif ($updateHvInNovasoft->isChildUpdate()) {
            try {
                $response = $this->scraper->updateChild($data);
            } catch (ScraperNotFoundException $e) {
                $this->scraperMessenger->insertToNovasoft($this->hvRepository->find($updateHvInNovasoft->getHvId()));
                throw new UnrecoverableMessageHandlingException();
            }
        }
        elseif ($updateHvInNovasoft->isChildDelete()) {
            try {
                $response = $this->scraper->deleteChild($data);
            } catch (ScraperNotFoundException $e) {
                throw new UnrecoverableMessageHandlingException();
            }
        }
        else {
            $response = $this->scraper->postHv($data);
        }


        $this->scraperMessenger->saveUpdateHvSuccess(
            $updateHvInNovasoft->getHvId(),
            $updateHvInNovasoft->getHvData(),
            $updateHvInNovasoft->getAction(),
            $response->getLog()
        );
    }
}