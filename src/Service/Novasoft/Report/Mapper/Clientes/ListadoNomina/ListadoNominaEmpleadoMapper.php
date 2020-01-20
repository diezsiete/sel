<?php

namespace App\Service\Novasoft\Report\Mapper\Clientes\ListadoNomina;

use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado;
use App\Event\Event\Novasoft\Report\Mapper\ListadoNominaNuevoObjetoEvent;
use App\Repository\Main\EmpleadoRepository;
use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleadoRepository;
use App\Service\Novasoft\Report\Exception\InvalidMappedObject;
use App\Service\Novasoft\Report\Mapper\DataFilter;
use App\Service\Novasoft\Report\Mapper\Mapper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListadoNominaEmpleadoMapper extends Mapper implements EventSubscriberInterface
{
    /**
     * @var ListadoNominaEmpleado
     */
    protected $targetObject;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepo;

    /**
     * @var ListadoNominaEmpleado
     */
    protected $currentTargetObject;

    /**
     * @var ListadoNominaEmpleado[]|ArrayCollection
     */
    protected $targets;

    public function __construct(DataFilter $filter, EmpleadoRepository $empleadoRepo)
    {
        parent::__construct($filter);
        $this->empleadoRepo = $empleadoRepo;
        $this->targets = new ArrayCollection();
    }

    protected function instanceTargetObject()
    {
        return new ListadoNominaEmpleado();
    }

    protected function defineMap(): array
    {
        return [
            'cod_emp' => 'empleado',
            'conv_suc' => 'sucursalCodigo',
            'conv_suc_des' => 'sucursalNombre',
            'cod_cont' => 'contrato',
            'nom_car' => 'nombreCargo',
            'fec_ing' => 'fechaIngreso',
            'sueldo' => 'sueldo',
            'por_ate' => 'riesgoCargo'
        ];
    }

    public function setEmpleado($ident)
    {
        // ya podemos sabes si es un grupo repetido
        $match = $this->targets->matching(ListadoNominaEmpleadoRepository::filterByIdentificacionCriteria($ident));
        if($match->count()) {
            $this->currentTargetObject = $match->first();
            $this->targetObject = null;
        } else {
            $empleado = $this->empleadoRepo->findByIdentificacion($ident);
            if(!$empleado) {
                throw new InvalidMappedObject("empleado con ident '$ident' no existe'");
            }
            $this->targetObject
                ->setIdentificacion($ident)
                ->setEmpleado($empleado);
        }
    }

    public function setFechaIngreso($value)
    {
        if($this->targetObject) {
            $fecha = $this->filter->fechaFromNovasoft($value);
            $this->targetObject->setFechaIngreso($fecha);
        }
    }

    public function setSueldo($value)
    {
        if($this->targetObject) {
            $sueldo = $this->filter->int($value);
            $this->targetObject->setSueldo($sueldo);
        }
    }

    public function setRiesgoCargo($value)
    {
        if($this->targetObject) {
            $value = $this->filter->float($value);
            $this->targetObject->setRiesgoCargo($value);
        }
    }

    public function addMappedObject(&$objects)
    {
        // si no definido, es porque estamos trabajando con uno previamente definido
        if($this->targetObject) {
            $objects[] = $this->targetObject;
            $this->targets->add($this->targetObject);
        } elseif($this->currentTargetObject) {
            $objects[] = $this->currentTargetObject;
        }
        $this->targetObject = $this->instanceTargetObject();
        $this->currentTargetObject = null;
    }

    public static function getSubscribedEvents()
    {
        return [
            ListadoNominaNuevoObjetoEvent::class => 'clearTargets'
        ];
    }

    public function clearTargets()
    {
        $this->targets->clear();
    }
}