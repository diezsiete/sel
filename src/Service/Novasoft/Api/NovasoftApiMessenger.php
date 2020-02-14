<?php
namespace App\Service\Novasoft\Api;

use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Message\Novasoft\Api\DeleteHvChildInNovasoft;
use App\Message\Novasoft\Api\InsertHvChildInNovasoft;
use App\Message\Novasoft\Api\InsertHvInNovasoft;
use App\Message\Novasoft\Api\UpdateHvChildInNovasoft;
use App\Message\Novasoft\Api\UpdateHvInNovasoft;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Enviar mensajes que se comunicaran a traves de messenger con novasoftapi
 * Class NovasoftApiMessenger
 * @package App\Service\Novasoft\Api
 */
class NovasoftApiMessenger
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    public function __construct(MessageBusInterface $messageBus, NormalizerInterface $normalizer, DenormalizerInterface $denormalizer)
    {
        $this->messageBus = $messageBus;
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
    }

    public function insert(Hv $hv)
    {
        $this->messageBus->dispatch(new InsertHvInNovasoft($hv->getId()));
    }

    public function update(Hv $hv)
    {
        $this->messageBus->dispatch(new UpdateHvInNovasoft($hv->getId()));
    }

    public function insertChild(HvEntity $entity, Hv $hv)
    {
        $this->messageBus->dispatch(new InsertHvChildInNovasoft($entity->getId(), get_class($entity), $hv->getId()));
    }

    public function updateChild(HvEntity $entity, Hv $hv)
    {
        $childNormalized = $this->normalizer->normalize($entity, null, ['groups' => ['messenger:hv-child:put', 'napi:hv-child:put']]);
        $this->messageBus->dispatch(new UpdateHvChildInNovasoft($childNormalized, get_class($entity), $hv->getId()));
    }

    public function deleteChild(HvEntity $entity, Hv $hv)
    {
        $napiId = $entity->getNapiId();
        $this->messageBus->dispatch(new DeleteHvChildInNovasoft($napiId, get_class($entity), $hv->getId()));
    }
}