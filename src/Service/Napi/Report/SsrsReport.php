<?php


namespace App\Service\Napi\Report;


use App\Entity\Main\Usuario;
use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Service\Novasoft\Api\Client\NapiClient;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(NapiClient $client, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->client = $client;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    protected function dispatchImportEvent($entity)
    {
        $this->dispatcher->dispatch(new ImportEvent($entity));
    }

    protected function dispatchDeleteEvent($equalIdentifier, $entityClass)
    {
        $this->dispatcher->dispatch(new DeleteEvent($equalIdentifier, $entityClass));
    }

    abstract public function import(Usuario $usuario);
}