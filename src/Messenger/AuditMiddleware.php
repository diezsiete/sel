<?php


namespace App\Messenger;


use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Stamp\SentToFailureTransportStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Stamp\ValidationStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

class AuditMiddleware implements MiddlewareInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var TransportInterface
     */
    private $transportScraper;

    public function __construct(LoggerInterface $messengerAuditLogger, TransportInterface $transportScraper)
    {
        $this->logger = $messengerAuditLogger;
        $this->transportScraper = $transportScraper;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(UniqueIdStamp::class)) {
            $envelope = $envelope->with(new UniqueIdStamp());
            //$envelope = $envelope->with(new SerializerStamp(['peito' => 'si']));
        }

        /** @var UniqueIdStamp $stamp */
        $stamp = $envelope->last(UniqueIdStamp::class);

        $context = [
            'id' => $stamp->getUniqueId(),
            'class' => get_class($envelope->getMessage())
        ];

        $envelope = $stack->next()->handle($envelope, $stack);

        /** @var TransportMessageIdStamp $messageIdStamp */
        if($messageIdStamp = $envelope->last(TransportMessageIdStamp::class)) {
            //$this->logger->info("MESSAGE ID: " . $messageIdStamp->getId());
        }

        if ($envelope->last(ReceivedStamp::class)) {
            //$this->logger->info('[{id}] Received {class}', $context);
        }elseif($envelope->last(SentStamp::class)){

            //$this->logger->info('[{id}] Sent {class}', $context);

        }else {
            //$this->logger->info('[{id}] Handling sync {class}', $context);
        }

        return $envelope;

    }


}