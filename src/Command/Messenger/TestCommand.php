<?php


namespace App\Command\Messenger;


use App\Messenger\Transport\Scraper\ScraperTransport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class TestCommand extends Command
{
    public static $defaultName = "sel:messenger:test";
    /**
     * @var TransportInterface
     */
    private $transportFailed;
    /**
     * @var ScraperTransport
     */
    private $scraperTransport;


    public function __construct(TransportInterface $transportFailed, TransportInterface $transportScraper)
    {
        parent::__construct();
        $this->transportFailed = $transportFailed;


        $this->scraperTransport = $transportScraper;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        dump($this->scraperTransport->getConnection()->hvIdHasFailed(1));
        /*$envelopes = $this->transportFailed->get();
        $rows = [];
        foreach ($envelopes as $envelope) {
            dump($envelope);
        }*/
    }
}