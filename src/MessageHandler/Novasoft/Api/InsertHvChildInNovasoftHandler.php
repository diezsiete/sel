<?php


namespace App\MessageHandler\Novasoft\Api;


use App\Entity\Hv\HvEntity;
use App\Message\Novasoft\Api\InsertHvChildInNovasoft;
use App\Service\Novasoft\Api\Client\HvClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class InsertHvChildInNovasoftHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var HvClient
     */
    private $client;

    public function __construct(EntityManagerInterface $em, HvClient $client)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function __invoke(InsertHvChildInNovasoft $message)
    {
        $this->em->clear();
        /** @var HvEntity $hvEntity */
        $hvEntity = $this->em->getRepository($message->getChildClass())->find($message->getChildId());
        if($hvEntity) {
            $this->client->postChild($hvEntity);
        }
    }
}