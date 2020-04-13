<?php


namespace App\Event\EventSubscriber\ServicioEmpleados;


use App\Entity\Novasoft\Report\LiquidacionContrato;

use App\Entity\Napi\Report\ServicioEmpleados\CertificadoIngresos;
use App\Entity\Napi\Report\ServicioEmpleados\Nomina;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoLaboral;

use App\Entity\ServicioEmpleados\CertificadoIngresos as SeCertificadoIngresos;
use App\Entity\ServicioEmpleados\Nomina as SeNomina;
use App\Entity\ServicioEmpleados\CertificadoLaboral as SeCertificadoLaboral;
use App\Entity\ServicioEmpleados\LiquidacionContrato as SeLiquidacionContrato;
use App\Entity\ServicioEmpleados\ServicioEmpleadosReport;
use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Repository\ServicioEmpleados\NominaRepository;
use DateTime;
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
        return (new SeNomina())
            ->setFecha($entity->getFechaFinal())
            ->setConvenio($entity->getNombreConvenio());
    }

    protected function importCertificadoIngresos(CertificadoIngresos $certificado)
    {
        $periodo = DateTime::createFromFormat('Y-m-d', $certificado->getFechaInicial()->format('Y') . '-01-01');
        return (new SeCertificadoIngresos())->setPeriodo($periodo);
    }

    protected function importCertificadoLaboral(CertificadoLaboral $certificadoLaboral)
    {
        return (new SeCertificadoLaboral())
            ->setFechaIngreso($certificadoLaboral->getFechaIngreso())
            ->setFechaRetiro($certificadoLaboral->getFechaRetiro())
            ->setConvenio($certificadoLaboral->getEmpresaUsuaria());
    }

    protected function importLiquidacionContrato(LiquidacionContrato $liquidacionContrato)
    {
        return (new SeLiquidacionContrato())
            ->setFechaIngreso($liquidacionContrato->getFechaIngreso())
            ->setFechaRetiro($liquidacionContrato->getFechaRetiro())
            ->setContrato($liquidacionContrato->getNumeroContrato());
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
        /** @var ServicioEmpleadosReport $seEntity */
        $seEntity = null;
        switch ($event->entityClass) {
            case Nomina::class:
                $seEntity = $this->importNomina($event->entity);
                $seEntity->setSourceNapi();
                break;
            case CertificadoLaboral::class:
                $seEntity = $this->importCertificadoLaboral($event->entity);
                $seEntity->setSourceNapi();
                break;
            case CertificadoIngresos::class:
                $seEntity = $this->importCertificadoIngresos($event->entity);
                $seEntity->setSourceNovasoft();
                break;
            case LiquidacionContrato::class:
                $seEntity = $this->importLiquidacionContrato($event->entity);
                $seEntity->setSourceNovasoft();
                break;
        }
        if($seEntity) {
            $seEntity
                ->setSourceId($event->entity->getId())
                ->setUsuario($event->entity->getUsuario());
            $this->em->persist($seEntity);
            $this->em->flush();
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