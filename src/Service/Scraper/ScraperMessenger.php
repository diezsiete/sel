<?php


namespace App\Service\Scraper;


use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Message\Scraper\DownloadAutoliquidacion;
use App\Message\Scraper\GenerateAutoliquidacion;
use App\Message\Scraper\UpdateHvInNovasoft;
use App\Message\Scraper\UpdateHvInNovasoftSuccess;
use App\Messenger\Stamp\ScraperHvSuccessStamp;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ScraperMessenger
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
     * @var string
     */
    private $appEnv;

    public function __construct(MessageBusInterface $messageBus, NormalizerInterface $normalizer, $appEnv)
    {
        $this->messageBus = $messageBus;
        $this->normalizer = $normalizer;
        $this->appEnv = $appEnv;
    }

    public function insertToNovasoft(Hv $hv)
    {
        try {
            $data = $this->normalizer->normalize($hv, null, ['groups' => ['scraper']]);
            $message = new UpdateHvInNovasoft($hv->getId(), $data, UpdateHvInNovasoft::ACTION_INSERT);
            $this->messageBus->dispatch($message);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }

    public function updateDatosBasicos(Hv $hv)
    {
        try {
            $data = $this->normalizer->normalize($hv, null, ['groups' => ['scraper-hv']]);

            $message = new UpdateHvInNovasoft($hv->getId(), $data, UpdateHvInNovasoft::ACTION_UPDATE);
            $this->messageBus->dispatch($message);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }

    public function insertChild(HvEntity $hvEntity)
    {
        try {
            $data = $this->normalizer->normalize($hvEntity->getHv(), null, [
                'groups' => ['scraper-hv-child'], 'scraper-hv-child' => $hvEntity]);
            $message = new UpdateHvInNovasoft($hvEntity->getHv()->getId(), $data, UpdateHvInNovasoft::ACTION_CHILD_INSERT);
            $this->messageBus->dispatch($message);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }

    public function updateChild(HvEntity $hvEntity)
    {
        try {
            $data = $this->normalizer->normalize($hvEntity->getHv(), null, [
                'groups' => ['scraper-hv-child'], 'scraper-hv-child' => get_class($hvEntity)]);
            $message = new UpdateHvInNovasoft($hvEntity->getHv()->getId(), $data, UpdateHvInNovasoft::ACTION_CHILD_UPDATE);
            $this->messageBus->dispatch($message);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }

    public function deleteChild(Hv $hv, string $childClass)
    {
        try {
            $data = $this->normalizer->normalize($hv, null, [
                'groups' => ['scraper-hv-child'], 'scraper-hv-child' => $childClass]);
            $message = new UpdateHvInNovasoft($hv->getId(), $data, UpdateHvInNovasoft::ACTION_CHILD_DELETE);
            $this->messageBus->dispatch($message);
        } catch (Exception $e) {
            $this->handleDispatchException($e);
        }
    }


    public function saveUpdateHvSuccess($hvId, $data, $action, $log)
    {
        $message = new UpdateHvInNovasoftSuccess($hvId, $data, $action);

        $this->messageBus->dispatch(new Envelope($message, [new ScraperHvSuccessStamp($log)]));
    }


    /**
     * @param AutoliquidacionEmpleado|int $autoliquidacionEmpleado
     */
    public function generateAutoliquidacion($autoliquidacionEmpleado)
    {
        $id = is_int($autoliquidacionEmpleado) ? $autoliquidacionEmpleado : $autoliquidacionEmpleado->getId();
        $this->messageBus->dispatch(new GenerateAutoliquidacion($id));
    }

    /**
     * @param AutoliquidacionEmpleado|int $autoliquidacionEmpleado
     */
    public function downloadAutoliquidacion($autoliquidacionEmpleado)
    {
        $id = is_int($autoliquidacionEmpleado) ? $autoliquidacionEmpleado : $autoliquidacionEmpleado->getId();
        $this->messageBus->dispatch(new DownloadAutoliquidacion($id));
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