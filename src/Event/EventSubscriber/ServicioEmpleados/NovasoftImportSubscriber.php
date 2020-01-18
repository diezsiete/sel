<?php


namespace App\Event\EventSubscriber\ServicioEmpleados;

use App\Entity\Novasoft\Report\CertificadoLaboral;
use App\Entity\Novasoft\Report\Nomina\Nomina;
use App\Entity\ServicioEmpleados\Nomina as SeNomina;
use App\Entity\ServicioEmpleados\CertificadoLaboral as SeCertificadoLaboral;
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

    protected function importCertificadoLaboral(CertificadoLaboral $certificadoLaboral)
    {
        $seCertificadoLaboral = (new SeCertificadoLaboral())
            ->setUsuario($certificadoLaboral->getUsuario())
            ->setFechaIngreso($certificadoLaboral->getFechaIngreso())
            ->setFechaRetiro($certificadoLaboral->getFechaEgreso())
            ->setConvenio($certificadoLaboral->getEmpresaUsuaria())
            ->setSourceNovasoft()
            ->setSourceId($certificadoLaboral->getId());

        $this->em->persist($seCertificadoLaboral);
        $this->em->flush();
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
        $seEntityClass = $this->getClassName(SeNomina::class, true) . '\\' . $this->getClassName($event->entityClass);
        if($entity = $this->em->getRepository($seEntityClass)->findOneBy(['sourceId' => $event->entityId])) {
            $this->em->remove($entity);
        }
    }

    /**
     * @param string $eventName
     * @param string $entityClass
     * @return bool|string
     */
    protected function getHandlerMethodName($eventName, $entityClass)
    {
        if($className = $this->getClassName($entityClass)) {
            $methodName = $eventName . ucfirst($className);
            if(method_exists($this, $methodName)) {
                return $methodName;
            }
        }
        return false;
    }

    protected function getClassName($entityClass, $getNamespace = false)
    {
        if(preg_match("/^(.+)\\\\(\w+)$/", $entityClass, $matches)) {
            return $getNamespace ? $matches[1] : $matches[2];
        }
        return false;
    }

}