<?php


namespace App\MessageHandler\Novasoft\Api;


use App\Entity\Hv\HvEntity;
use App\Exception\Novasoft\Api\NotFoundException;
use App\Message\Novasoft\Api\UpdateHvChildInNovasoft;
use App\Service\Novasoft\Api\Client\HvClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateHvChildInNovasoftHandler implements MessageHandlerInterface
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

    public function __invoke(UpdateHvChildInNovasoft $message)
    {
        $this->em->clear();
        /** @var HvEntity $hvEntity */
        $hvEntity = $this->em->getRepository($message->getChildClass())->find($message->getChildId());
        if($hvEntity) {
            try {
                $this->client->putChild($hvEntity);
            } catch(NotFoundException $e) {
                $this->client->postChild($hvEntity);
            }

        }
    }
}