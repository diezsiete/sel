<?php


namespace App\Messenger;


use App\Entity\Scraper\Solicitud;
use App\Messenger\Stamp\ScraperSolcitudStamp;
use App\Repository\Scraper\SolicitudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;

class ScraperSolicitudMiddleware implements MiddlewareInterface
{

    /**
     * @var SolicitudRepository
     */
    private $solicitudRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SolicitudRepository $solicitudRepository, EntityManagerInterface $em)
    {
        $this->solicitudRepository = $solicitudRepository;
        $this->em = $em;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /*dump(">>>BEFORE");
        foreach($envelope->all() as $stamps) {
            foreach($stamps as $stamp) {
                dump(get_class($stamp));
            }
        }
        dump("<<<BEFORE");*/

        $this->setSolicitudEstado($envelope, ReceivedStamp::class, SolicitudRepository::EJECUTANDO);

        $envelope = $stack->next()->handle($envelope, $stack);

        /*dump(">>>AFTER");
        foreach($envelope->all() as $stamps) {
            foreach($stamps as $stamp) {
                dump(get_class($stamp));
            }
        }
        dump("<<<AFTER");*/

        if($envelope->last(RedeliveryStamp::class)) {
            $this->setSolicitudEstado($envelope, TransportMessageIdStamp::class, SolicitudRepository::EJECUTADO_ERROR);
        }
        elseif(!$this->setSolicitudEstado($envelope, HandledStamp::class, SolicitudRepository::EJECUTADO_EXITO)) {
            $this->setSolicitudEstado($envelope, TransportMessageIdStamp::class, SolicitudRepository::ESPERANDO_EN_COLA);
        }
        

        return $envelope;
    }


    private function setSolicitudEstado(Envelope $envelope, $stateStampFqcn, $estado)
    {
        /** @var ScraperSolcitudStamp $scraperSolicitudStamp */
        if($scraperSolicitudStamp = $envelope->last(ScraperSolcitudStamp::class)) {
            /** @var TransportMessageIdStamp|StampInterface $stateStamp */
            if($stateStamp = $envelope->last($stateStampFqcn)) {
                if($solicitud = $this->solicitudRepository->find($scraperSolicitudStamp->getSolicitudId())) {
                    $solicitud->setEstado($estado);
                    if($stateStampFqcn === TransportMessageIdStamp::class) {
                        $solicitud->setMessageId($stateStamp->getId());
                    }
                    elseif($estado === SolicitudRepository::EJECUTADO_EXITO) {
                        $solicitud->setMessageId(null);
                    }
                    $this->em->flush();
                    return true;
                }
            }
        }
        return false;
    }
}