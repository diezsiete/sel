<?php


namespace App\Messenger;


use App\Message\Scraper\UpdateHvInNovasoft;
use App\Message\Scraper\UpdateResponseReceived;
use App\Messenger\Stamp\ScraperHvSuccessStamp;
use App\Service\Scraper\Exception\ScraperClientException;
use App\Service\Scraper\Exception\ScraperException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;

class ScraperResponseMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(LoggerInterface $messengerAuditLogger, MessageBusInterface $messageBus)
    {
        $this->logger = $messengerAuditLogger;
        $this->messageBus = $messageBus;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /** @var ScraperHvSuccessStamp|null $scraperHvSuccessStamp */
        if($scraperHvSuccessStamp = $envelope->last(ScraperHvSuccessStamp::class)) {
            $envelope = $envelope->withoutAll(ScraperHvSuccessStamp::class);
        }

        $envelope = $stack->next()->handle($envelope, $stack);

        /** @var TransportMessageIdStamp|null $transportMessageIdStamp */
        if($scraperHvSuccessStamp && $transportMessageIdStamp = $envelope->last(TransportMessageIdStamp::class)) {
            $this->messageBus->dispatch(new UpdateResponseReceived($transportMessageIdStamp->getId(), $scraperHvSuccessStamp->getLog()));
        }

        $this->handleHvError($envelope);

        return $envelope;
    }

    /**
     * Detecta si hubo una excepcion y agrega log al registro del mensaje
     * @param Envelope $envelope
     */
    private function handleHvError(Envelope $envelope)
    {
        if($envelope->getMessage() instanceof UpdateHvInNovasoft) {
            /** @var RedeliveryStamp $redeliveryStamp */
            $redeliveryStamp = $envelope->last(RedeliveryStamp::class);
            /** @var TransportMessageIdStamp $transportMessageIdStamp */
            $transportMessageIdStamp = $envelope->last(TransportMessageIdStamp::class);
            if ($redeliveryStamp && $transportMessageIdStamp) {
                if ($flattenException = $redeliveryStamp->getFlattenException()) {
                    if ($flattenException->getClass() === ScraperException::class) {
                        $log = implode("\n", $flattenException->getHeaders());
                    } elseif ($flattenException->getClass() === ScraperClientException::class) {
                        $log = $flattenException->getMessage();
                    } else {
                        $log = "UNEXPECTED EXCEPTION ({$flattenException->getMessage()}) \n\n" . $flattenException->getTraceAsString();
                    }
                    $this->messageBus->dispatch(new UpdateResponseReceived($transportMessageIdStamp->getId(), $log, false));
                }
            }
        }
    }
}