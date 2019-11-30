<?php


namespace App\Messenger;


use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;

class AuditMiddleware implements MiddlewareInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $messengerAuditLogger)
    {
        $this->logger = $messengerAuditLogger;
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