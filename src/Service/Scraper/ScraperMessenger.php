<?php


namespace App\Service\Scraper;


use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Autoliquidacion\AutoliquidacionProgreso;
use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Message\Scraper\DownloadAutoliquidacion;
use App\Message\Scraper\GenerateAutoliquidacion;
use App\Message\Scraper\UpdateHvInNovasoft;
use App\Message\Scraper\UpdateHvInNovasoftSuccess;
use App\Messenger\Stamp\ScraperHvSuccessStamp;
use App\Service\Configuracion\Configuracion;
use App\Service\Exec;
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
    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var Exec
     */
    private $exec;

    public function __construct(MessageBusInterface $messageBus, NormalizerInterface $normalizer, $appEnv,
                                Configuracion $configuracion, Exec $exec)
    {
        $this->messageBus = $messageBus;
        $this->normalizer = $normalizer;
        $this->appEnv = $appEnv;
        $this->configuracion = $configuracion;
        $this->exec = $exec;
    }

    public function insertToNovasoft(Hv $hv)
    {
        try {
            $this->autoConsume();
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
            $this->autoConsume();
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
            $this->autoConsume();
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
            $this->autoConsume();
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
            $this->autoConsume();
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

    private function autoConsume()
    {
        if($this->configuracion->getScraper()->isAutoConsume() && !$this->exec->uniqueExists('messenger')) {
            $command = $this->configuracion->getScraper()->getConsumeCommand();
            $this->exec->asyncUnique($command, 'messenger');
        }
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