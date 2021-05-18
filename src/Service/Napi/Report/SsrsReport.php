<?php

namespace App\Service\Napi\Report;

use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Service\Configuracion\Configuracion;
use App\Service\Napi\Client\NapiClient;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class SsrsReport
{
    /**
     * @var NapiClient
     */
    protected $client;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ServicioEmpleadosReport
     */
    protected $currentReport;
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;
    /**
     * @var Configuracion
     */
    protected $configuracion;

    public function __construct(NapiClient $client, EntityManagerInterface $em, EventDispatcherInterface $dispatcher, Configuracion $configuracion)
    {
        $this->client = $client;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->configuracion = $configuracion;
    }

    public function setReport($report): self
    {
        $this->currentReport = $report;
        return $this;
    }

    public function linkPdf()
    {
        $ssrsDbs = $this->configuracion->getSsrsDb();
        if(count($ssrsDbs) > 1 && $this->currentReport->getUsuario()
            && $empleado = $this->em->getRepository(Empleado::class)->findByIdentificacion($this->currentReport->getUsuario()->getIdentificacion())) {
            $this->client->db($empleado->getSsrsDb());
        }
        $response = $this->client->itemOperations($this->currentReport, false)->get();
        if($response && isset($response['hydra:member']) && count($response['hydra:member']) === 1) {
            return $response['hydra:member'][0];
        }
        throw new \RuntimeException('Error generando link PDF');
    }

    protected function dispatchImportEvent($entity)
    {
        $this->dispatcher->dispatch(new ImportEvent($entity));
    }

    protected function dispatchDeleteEvent($equalIdentifier, $entityClass)
    {
        $this->dispatcher->dispatch(new DeleteEvent($equalIdentifier, $entityClass));
    }

    /**
     * @param Usuario|Empleado $usuario
     * @throws Exception
     */
    public function import($usuario)
    {
        $ssrsDbs = $this->configuracion->getSsrsDb();
        $empleado = $usuario instanceof Usuario
            ? $this->em->getRepository(Empleado::class)->findByIdentificacion($usuario->getIdentificacion())
            : $usuario;
        if(count($ssrsDbs) > 1 && $empleado) {
            $this->client->db($empleado->getSsrsDb());
        }
        $objects = $this->callOperation($empleado);
        if($objects) {
            $objects = is_iterable($objects) ? $objects : [$objects];
            foreach($objects as $object) {
                if (!$object->getId()) {
                    $object->setUsuario($usuario);

                    $this->em->persist($object);
                    $this->em->flush();

                    $this->dispatchImportEvent($object);
                } else {
                    $this->em->flush();
                }
            }
        }
    }

    abstract protected function callOperation(Empleado $empleado);
}