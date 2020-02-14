<?php

namespace App\MessageHandler\Novasoft\Api;

use App\Exception\Novasoft\Api\NotFoundException;
use App\Message\Novasoft\Api\DeleteHvChildInNovasoft;
use App\Service\Novasoft\Api\Client\HvClient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteHvChildInNovasoftHandler implements MessageHandlerInterface
{
    /**
     * @var HvClient
     */
    private $client;

    public function __construct(HvClient $client)
    {
        $this->client = $client;
    }

    public function __invoke(DeleteHvChildInNovasoft $message)
    {
        try {
            $this->client->deleteChild($message->getNapiId(), $message->getChildClass());
        } catch (NotFoundException $e) {
            // TODO validar que si no hay mas registros del child, en novasoft tampoco hallan
        }

    }
}