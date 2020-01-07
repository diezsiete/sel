<?php


namespace App\Service\Scraper;


use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Autoliquidacion\AutoliquidacionProgreso;
use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Entity\Scraper\Solicitud;
use App\Message\Scraper\DownloadAutoliquidacion;
use App\Message\Scraper\GenerateAutoliquidacion;
use App\Message\Scraper\InsertHvChildInNovasoft;
use App\Message\Scraper\InsertHvInNovasoft;
use App\Message\Scraper\UpdateHvChildsInNovasoft;
use App\Message\Scraper\UpdateHvDatosBasicosInNovasoft;
use App\Messenger\Stamp\ScraperSolcitudStamp;
use App\Service\Configuracion\Configuracion;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ScraperMessenger
{

    use HvNovasoftHandler;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;
    /**
     * @var string
     */
    private $appEnv;
    /**
     * @var Configuracion
     */
    private $configuracion;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(MessageBusInterface $messageBus, NormalizerInterface $normalizer, $appEnv,
                                Configuracion $configuracion, EntityManagerInterface $em)
    {
        $this->messageBus = $messageBus;
        $this->normalizer = $normalizer;
        $this->appEnv = $appEnv;
        $this->configuracion = $configuracion;
        $this->em = $em;
    }

    public function updateDatosBasicos(Hv $hv)
    {
        try {
            $data = $this->normalizeDatosBasicos($hv, $this->normalizer);
            $this->dispatchScraperSolicitud($hv, $data, UpdateHvDatosBasicosInNovasoft::class);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }


    public function insertToNovasoft(Hv $hv)
    {
        try {
            $data = $this->normailizeFullHv($hv, $this->normalizer);
            $this->dispatchScraperSolicitud($hv, $data, InsertHvInNovasoft::class);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }


    public function insertChild(HvEntity $hvEntity)
    {
        try {
            $data = $this->normalizeOneHvChild($hvEntity, $this->normalizer);
            $message = new InsertHvChildInNovasoft($hvEntity->getId(), get_class($hvEntity));
            $this->dispatchScraperSolicitud($hvEntity->getHv(), $data, $message);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }

    public function updateChild(Hv $hv, $childClass)
    {
        try {
            $data = $this->normalizeHvChilds($hv, $childClass, $this->normalizer);
            $this->dispatchScraperSolicitud($hv, $data, new UpdateHvChildsInNovasoft($childClass));
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }


    /**
     * @param AutoliquidacionEmpleado|int $autoliquidacionEmpleado
     * @param AutoliquidacionProgreso|int $autoliquidacionProgreso
     */
    public function generateAutoliquidacion($autoliquidacionEmpleado, $autoliquidacionProgreso)
    {
        $id = is_int($autoliquidacionEmpleado) ? $autoliquidacionEmpleado : $autoliquidacionEmpleado->getId();
        $progresoId = is_int($autoliquidacionProgreso) ? $autoliquidacionProgreso : $autoliquidacionProgreso->getId();
        $this->messageBus->dispatch(new GenerateAutoliquidacion($id, $progresoId));
    }

    /**
     * @param AutoliquidacionEmpleado|int $autoliquidacionEmpleado
     * @param AutoliquidacionProgreso|int $autoliquidacionProgreso
     */
    public function downloadAutoliquidacion($autoliquidacionEmpleado, $autoliquidacionProgreso)
    {
        $id = is_int($autoliquidacionEmpleado) ? $autoliquidacionEmpleado : $autoliquidacionEmpleado->getId();
        $progresoId = is_int($autoliquidacionProgreso) ? $autoliquidacionProgreso : $autoliquidacionProgreso->getId();
        $this->messageBus->dispatch(new DownloadAutoliquidacion($id, $progresoId));
    }


    private function dispatchScraperSolicitud(Hv $hv, $data, $messageClass)
    {
        $solicitud = (new Solicitud())
            ->setHv($hv)
            ->setData(json_encode($data))
            ->setCreatedAt(new DateTime())
            ->setMessageClass(is_string($messageClass) ? $messageClass : get_class($messageClass));

        $this->em->persist($solicitud);
        $this->em->flush();

        $message = is_string($messageClass) ? new $messageClass($solicitud->getId()) : $messageClass->setSolicitudId($solicitud->getId());
        $envelope = new Envelope($message, [
            new ScraperSolcitudStamp($solicitud->getId())
        ]);

        $this->messageBus->dispatch($envelope);
    }


    /**
     * Logear y manejar exepciones excepcionales de serailizacion de datos o de despacho de mensajes
     * @param Exception $e
     * @throws Exception
     */
    private function handleDispatchException(Exception $e)
    {
        if($this->appEnv === 'dev') {
            throw $e;
        } else {
            // TODO
        }
    }

}