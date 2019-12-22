<?php


namespace App\Service\Scraper;


use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Entity\Scraper\Solicitud;
use App\Service\Scraper\Response\ScraperStreamResponseEvent;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

trait HvNovasoftHandler
{
    /**
     * @var Solicitud
     */
    protected $currentSolicitud;

    public function onStreamResponse(ScraperStreamResponseEvent $responseEvent)
    {
        if($this->currentSolicitud) {
            $this->currentSolicitud->addLog($responseEvent->getContent());
            $this->em->flush();
        }
    }

    /**
     * @param EntityManagerInterface $em
     * @param $solicitudId
     * @param LoggerInterface $logger
     * @return Solicitud
     * @throws Exception
     */
    protected function getSolicitud(EntityManagerInterface $em, $solicitudId, LoggerInterface $logger)
    {
        $solicitud = $em->getRepository(Solicitud::class)->find($solicitudId);
        if($solicitud) {
            $this->currentSolicitud = $solicitud;
            return $this->currentSolicitud;
        } else {
            $errorMessage = "solicitud '$solicitudId' not found";
            $logger->error($errorMessage);
            throw new Exception($errorMessage);
        }
    }

    protected function normailizeFullHv(Hv $hv, NormalizerInterface $normalizer)
    {
        return $normalizer->normalize($hv, null, ['groups' => ['scraper']]);
    }

    protected function normalizeDatosBasicos(Hv $hv, NormalizerInterface $normalizer)
    {
        return $normalizer->normalize($hv, null, ['groups' => ['scraper-hv']]);
    }

    protected function normalizeOneHvChild(HvEntity $hvEntity, NormalizerInterface $normalizer)
    {
        return $normalizer->normalize($hvEntity->getHv(), null, [
            'groups' => ['scraper-hv-child'], 'scraper-hv-child' => $hvEntity]);
    }

    protected function normalizeHvChilds(Hv $hv, $childClass, NormalizerInterface $normalizer)
    {
        return $normalizer->normalize($hv, null, ['groups' => ['scraper-hv-child'], 'scraper-hv-child' => $childClass]);
    }

    protected function subscribeToStreamResponse(EventDispatcherInterface $eventDispatcher)
    {
        $eventDispatcher->addListener(ScraperStreamResponseEvent::class, [$this, 'onStreamResponse']);
    }

    protected function unsubscribeToStreamResponse(EventDispatcherInterface $eventDispatcher)
    {
        $eventDispatcher->removeListener(ScraperStreamResponseEvent::class, [$this, 'onStreamResponse']);
    }
}