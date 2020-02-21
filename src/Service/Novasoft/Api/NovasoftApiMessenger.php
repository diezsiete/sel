<?php
namespace App\Service\Novasoft\Api;

use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Entity\Scraper\Solicitud;
use App\Message\Novasoft\Api\DeleteHvChildInNovasoft;
use App\Message\Novasoft\Api\InsertHvChildInNovasoft;
use App\Message\Novasoft\Api\InsertHvInNovasoft;
use App\Message\Novasoft\Api\UpdateHvChildInNovasoft;
use App\Message\Novasoft\Api\UpdateHvInNovasoft;
use App\Messenger\Stamp\ScraperSolcitudStamp;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
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
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(MessageBusInterface $messageBus, NormalizerInterface $normalizer,
                                DenormalizerInterface $denormalizer, EntityManagerInterface $em)
    {
        $this->messageBus = $messageBus;
        $this->normalizer = $normalizer;
        $this->em = $em;
        $this->denormalizer = $denormalizer;
    }

    public function insert(Hv $hv)
    {
        $message = new InsertHvInNovasoft($hv->getId());
//        $this->messageBus->dispatch($message);
        $this->dispatchMessageWithSolicitud($hv, $message);
    }

    public function update(Hv $hv)
    {
        $message = new UpdateHvInNovasoft($hv->getId());
        //$this->messageBus->dispatch($message);
        $this->dispatchMessageWithSolicitud($hv, $message, [$hv->getId()]);
    }

    public function insertChild(HvEntity $entity, Hv $hv)
    {
        $message = new InsertHvChildInNovasoft($entity->getId(), get_class($entity), $hv->getId());
        //$this->messageBus->dispatch($message);
        $this->dispatchMessageWithSolicitud($hv, $message);
    }

    public function updateChild(HvEntity $entity, Hv $hv)
    {
        $childNormalized = $this->normalizer->normalize($entity, null, ['groups' => ['messenger:hv-child:put', 'napi:hv-child:put']]);
        $message = new UpdateHvChildInNovasoft($childNormalized, get_class($entity), $hv->getId());
//        $this->messageBus->dispatch($message);
        $this->dispatchMessageWithSolicitud($hv, $message);
    }

    public function deleteChild(HvEntity $entity, Hv $hv)
    {
        $napiId = $entity->getNapiId();
        $message = new DeleteHvChildInNovasoft($napiId, get_class($entity), $hv->getId());
//        $this->messageBus->dispatch($message);
        $this->dispatchMessageWithSolicitud($hv, $message);
    }

    private function dispatchMessageWithSolicitud(Hv $hv, $message, $data = [])
    {
        $solicitud = (new Solicitud())
            ->setHv($hv)
            ->setCreatedAt(new DateTime())
            ->setMessageClass(get_class($message));

        $this->em->persist($solicitud);
        $this->em->flush();

        $message->setSolicitudId($solicitud->getId());
        $envelope = new Envelope($message, [
            new ScraperSolcitudStamp($solicitud->getId())
        ]);

        $this->messageBus->dispatch($envelope);
    }
}