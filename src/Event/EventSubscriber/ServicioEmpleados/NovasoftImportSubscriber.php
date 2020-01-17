<?php


namespace App\Event\EventSubscriber\ServicioEmpleados;

use App\Entity\Novasoft\Report\Nomina\Nomina;
use App\Entity\ServicioEmpleados\Nomina as SeNomina;
use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Repository\ServicioEmpleados\NominaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NovasoftImportSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var NominaRepository
     */
    private $nominaRepo;

    public function __construct(EntityManagerInterface $em, NominaRepository $nominaRepo)
    {
        $this->em = $em;
        $this->nominaRepo = $nominaRepo;
    }


    protected function importNomina(Nomina $entity)
    {
        $seNomina = (new SeNomina())
            ->setUsuario($entity->getUsuario())
            ->setFecha($entity->getFecha())
            ->setConvenio($entity->getConvenioCodigoNombre())
            ->setSourceNovasoft()
            ->setSourceId($entity->getId());

        $this->em->persist($seNomina);
        $this->em->flush();
    }

    protected function deleteNomina($entityId)
    {
        if($seNomina = $this->nominaRepo->findOneBy(['sourceId' => $entityId])) {
            $this->em->remove($seNomina);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ImportEvent::class => 'importEvent',
            DeleteEvent::class => 'deleteEvent'
        ];
    }

    public function importEvent(ImportEvent $event)
    {
        if($handlerMethodName = $this->getHandlerMethodName('import', $event->entityClass)) {
            $this->$handlerMethodName($event->entity);
        }
    }

    public function deleteEvent(DeleteEvent $event)
    {
        if($handlerMethodName = $this->getHandlerMethodName('delete', $event->entityClass)) {
            $this->$handlerMethodName($event->entityId);
        }
    }

    /**
     * @param string $eventName
     * @param string $entityClass
     * @return bool|string
     */
    protected function getHandlerMethodName($eventName, $entityClass)
    {
        if(preg_match("/\\\\(\w+)$/", $entityClass, $matches)) {
            $methodName = $eventName . ucfirst($matches[1]);
            if(method_exists($this, $methodName)) {
                return $methodName;
            }
        }
        return false;
    }
}