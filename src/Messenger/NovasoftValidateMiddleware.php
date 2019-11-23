<?php


namespace App\Messenger;


use App\Message\UploadToNovasoft;
use App\Messenger\Transport\Scraper\ScraperTransport;
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
     * @var ScraperTransport
     */
    private $transportScraper;

    public function __construct(LoggerInterface $messengerAuditLogger, TransportInterface $transportScraper)
    {
        $this->logger = $messengerAuditLogger;
        $this->transportScraper = $transportScraper;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        if($message instanceof UploadToNovasoft) {
            if(null === $envelope->last(SentToFailureTransportStamp::class)) {
                /** @var UniqueIdStamp $stamp */
                $stamp = $envelope->last(UniqueIdStamp::class);
                $hasPreviousFailed = $this->transportScraper->hvIdHasFailed($message->getHvId());

                $context = ['id' => $stamp->getUniqueId(), 'class' => get_class($message), 'hasPreviousFailed' => $hasPreviousFailed];

                if($hasPreviousFailed) {
                    $envelope = $this->envelopeToFailureTransport($envelope);
                }

                $this->logger->info('[{id}] {class} Not failed, hasPreviousFailed: {hasPreviousFailed} ', $context);
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