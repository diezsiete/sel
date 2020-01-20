<?php


namespace App\Service\Novasoft\Report\Mapper\Clientes\ListadoNomina;


use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupo;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoTotal;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaSubgrupo;
use App\Event\Event\Novasoft\Report\Mapper\ListadoNominaNuevoObjetoEvent;
use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoRepository;
use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoTotalRepository;
use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaSubGrupoRepository;
use App\Service\Novasoft\Report\Mapper\DataFilter;
use App\Service\Novasoft\Report\Mapper\Mapper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListadoNominaGrupoMapper extends Mapper implements EventSubscriberInterface
{
    /**
     * @var ListadoNominaGrupo
     */
    protected $targetObject;

    /**
     * @var ListadoNominaGrupo
     */
    protected $currentTargetObject;

    /**
     * @var ListadoNominaGrupo[]|ArrayCollection
     */
    protected $targets;


    public function __construct(DataFilter $filter)
    {
        parent::__construct($filter);
        $this->targets = new ArrayCollection();
    }

    protected function instanceTargetObject()
    {
        return new ListadoNominaGrupo();
    }

    protected function defineMap(): array
    {
        return [
            'nat_liq2' => 'nombre',
            'textbox907' => 'valorTotal',
            'grupoTotal' => [ListadoNominaGrupoTotalMapper::class => [
                'val_liq1',
                'cod_emp'
            ]],
            'subgrupo' => [ListadoNominaSubgrupoMapper::class => [
                'nom_con2',
                'textbox904',
                'textbox905',
                'can_liq',
                'val_liq',

                'cod_emp'
            ]]
        ];
    }

    public function setNombre($value)
    {
        // ya podemos sabes si es un grupo repetido
        $match = $this->targets->matching(ListadoNominaGrupoRepository::filterByNombreCriteria($value));
        if($match->count()) {
            $this->currentTargetObject = $match->first();
            $this->targetObject = null;
        } else {
            $this->targetObject->setNombre($value);
        }
    }

    public function setValorTotal($value)
    {
        if($this->targetObject) {
            $this->targetObject->setValorTotal($this->filter->int($value));
        }
    }

    /**
     * @param ListadoNominaSubgrupo $value
     */
    public function setSubgrupo($value)
    {
        $criteria = ListadoNominaSubGrupoRepository::filterByCodigoCriteria($value->getCodigo());
        $matching = $this->getTargetObject()->getSubgrupos()->matching($criteria);
        if(!$matching->count()) {
            $this->getTargetObject()->addSubgrupo($value);
        }
    }

    /**
     * @param ListadoNominaGrupoTotal $value
     */
    public function setGrupoTotal($value)
    {
        // ya podemos sabes si es repetido
        $match = $this->getTargetObject()->getTotales()->matching(ListadoNominaGrupoTotalRepository::filterByIdentCriteria($value->getIdentificacion()));
        if(!$match->count()) {
            $this->getTargetObject()->addTotal($value);
        }
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

    /**
     * @return ListadoNominaGrupo
     */
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