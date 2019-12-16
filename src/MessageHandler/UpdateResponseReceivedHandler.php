<?php


namespace App\MessageHandler;


use App\Entity\Scraper\MessageHv;
use App\Entity\Scraper\MessageHvSuccess;
use App\Message\Scraper\UpdateResponseReceived;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateResponseReceivedHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(UpdateResponseReceived $updateResponseReceived)
    {
        $messageId = $updateResponseReceived->getMessageId();
        $className = $updateResponseReceived->isSuccess() ? MessageHvSuccess::class : MessageHv::class;
        /** @var MessageHv|MessageHvSuccess $message */
        $message = $this->em->getRepository($className)->find($messageId);
        if($message) {
            $message->setLog($updateResponseReceived->getLog());
            $this->em->flush();
        } else {
            // TODO log this
            //dump("ERROR MESSAGE CON ID '$messageId' not found");
        }
    }
}