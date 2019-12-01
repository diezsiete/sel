<?php


namespace App\Messenger;


use App\Message\Scraper\UpdateHvInNovasoft;
use App\Messenger\Transport\Scraper\ScraperHvTransport;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Stamp\SentToFailureTransportStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Stamp\ValidationStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

class NovasoftValidateMiddleware implements MiddlewareInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ScraperHvTransport
     */
    private $transportScraper;

    public function __construct(LoggerInterface $messengerAuditLogger, TransportInterface $transportScraperHv)
    {
        $this->logger = $messengerAuditLogger;
        $this->transportScraper = $transportScraperHv;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        if($message instanceof UpdateHvInNovasoft) {
            if(null === $envelope->last(SentToFailureTransportStamp::class)) {
                $hasPreviousFailed = $this->transportScraper->hvIdHasFailed($message->getHvId());

                $context = ['class' => get_class($message), 'hasPreviousFailed' => $hasPreviousFailed];

                if($hasPreviousFailed) {
                    $envelope = $this->envelopeToFailureTransport($envelope);
                }

                $this->logger->info('{class} Not failed, hasPreviousFailed: {hasPreviousFailed} ', $context);
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }

    private function envelopeToFailureTransport(Envelope $envelope)
    {
        return $envelope
            ->withoutAll(ReceivedStamp::class)
            ->withoutAll(TransportMessageIdStamp::class)
            ->withoutAll(ValidationStamp::class)
            ->with(new SentToFailureTransportStamp('scraper'))
            ->with(new DelayStamp(0))
            ->with(new RedeliveryStamp(0, 'failed', "has previous failed", null));
    }
}