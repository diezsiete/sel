<?php


namespace App\Service\Novasoft\Report\Mapper\Clientes\ListadoNomina;


use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaRenglon;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaSubgrupo;
use App\Event\Event\Novasoft\Report\Mapper\ListadoNominaNuevoObjetoEvent;
use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaSubGrupoRepository;
use App\Service\Novasoft\Report\Mapper\DataFilter;
use App\Service\Novasoft\Report\Mapper\Mapper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ListadoNominaSubgrupoMapper extends Mapper implements EventSubscriberInterface
{
    /**
     * @var ListadoNominaSubgrupo
     */
    protected $targetObject;

    /**
     * @var ListadoNominaSubgrupo
     */
    protected $currentTargetObject;

    /**
     * @var ListadoNominaSubgrupo[]|ArrayCollection
     */
    protected $targets;

    public function __construct(DataFilter $filter)
    {
        parent::__construct($filter);
        $this->targets = new ArrayCollection();
    }

    protected function instanceTargetObject()
    {
        return new ListadoNominaSubgrupo();
    }

    protected function defineMap(): array
    {
        return [
            'nom_con2' => 'nombre',
            'textbox904' => 'cantidadTotal',
            'textbox905' => 'valorTotal',
            'renglon' => [ListadoNominaRenglonMapper::class => [
                'can_liq',
                'val_liq',
                'cod_emp'
            ]]
        ];
    }

    public function setNombre($value)
    {
        preg_match('/(.+?)(\d+)$/', $value, $matches);
        $nombre = $matches[1];
        $codigo = $matches[2];


        $criteria = $this->targets->matching(ListadoNominaSubGrupoRepository::filterByCodigoCriteria($codigo));
        if($criteria->count()) {
            $this->currentTargetObject = $criteria->first();
            $this->targetObject = null;
        } else {
            $this->targetObject
                ->setNombre($nombre)
                ->setCodigo($codigo);
        }
    }

    public function setCantidadTotal($value)
    {
        if($this->targetObject) {
            $this->targetObject->setCantidadTotal($this->filter->float($value));
        }
    }

    public function setValorTotal($value)
    {
        if($this->targetObject) {
            $this->targetObject->setValorTotal($this->filter->int($value));
        }
    }

    /**
     * @param ListadoNominaRenglon $value
     */
    public function setRenglon($value)
    {
        $this->getTargetObject()->addRenglon($value);
    }

    public function addMappedObject(&$objects)
    {
        // si no definido, es porque estamos trabajando con uno previamente definido
        if($this->targetObject) {
            $objects[] = $this->targetObject;
            $this->targets->add($this->targetObject);
        }
        else {
            $objects[] = $this->currentTargetObject;
        }
        $this->targetObject = $this->instanceTargetObject();
        $this->currentTargetObject = null;
    }

    public function getTargetObject()
    {
        return $this->targetObject ? $this->targetObject : $this->currentTargetObject;
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