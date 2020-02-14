<?php


namespace App\MessageHandler\Novasoft\Api;


use App\Entity\Hv\HvEntity;
use App\Exception\Novasoft\Api\NotFoundException;
use App\Message\Novasoft\Api\UpdateHvChildInNovasoft;
use App\Service\Novasoft\Api\Client\HvClient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UpdateHvChildInNovasoftHandler implements MessageHandlerInterface
{
    /**
     * @var HvClient
     */
    private $client;
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    public function __construct(HvClient $client, DenormalizerInterface $denormalizer)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
    }

    public function __invoke(UpdateHvChildInNovasoft $message)
    {

        /** @var HvEntity $hvEntity */
        $hvEntity = $this->denormalizer->denormalize(
            $message->getChildNormalized(), $message->getChildClass(), null, ['groups' => ['messenger:hv-child:put', 'napi:hv-child:put']]
        );
        try {
            $this->client->putChild($hvEntity);
        } catch(NotFoundException $e) {
            $this->client->postChild($hvEntity);
        }
    }
}