<?php

namespace App\Command;

use App\Service\Novasoft\Report\Report\LiquidacionNominaReport;
use App\Service\Novasoft\Report\Report\TrabajadoresActivosReport;
use App\Service\Scraper\HvScraper;
use App\Service\Scraper\NovasoftScraper;
use App\Service\Scraper\Response\ScraperStreamResponseEvent;
use App\Service\Scraper\ScraperClient;
use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class SelTestCommand extends Command
{
    protected static $defaultName = 'sel:test';
    /**
     * @var ScraperClient
     */
    private $scraperClient;
    /**
     * @var NovasoftScraper
     */
    private $novasoftScraper;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ScraperClient $scraperClient, NovasoftScraper $novasoftScraper, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();

        $this->scraperClient = $scraperClient;
        $this->novasoftScraper = $novasoftScraper;
        $this->eventDispatcher = $eventDispatcher;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*$response = $this->scraperClient->get('/test/stream', [], null);

        if (200 !== $response->getStatusCode()) {
            throw new Exception("response status code : {$response->getStatusCode()}");
        }

        $responses = [];
        foreach ($this->scraperClient->getHttpClient()->stream($response) as $chunk) {
            $responses[] = $chunk->getContent();
            $output->writeln($responses[count($responses) - 1]);
        }
        dump($responses);*/

        $this->eventDispatcher->addListener(ScraperStreamResponseEvent::class, function (ScraperStreamResponseEvent $event) use ($output, &$count) {
            $output->writeln($event->getContent());
        });

        $response = $this->novasoftScraper->home();
        dump($response);
    }
}
