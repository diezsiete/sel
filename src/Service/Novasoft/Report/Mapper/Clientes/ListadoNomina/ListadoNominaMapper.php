<?php


namespace App\Service\Novasoft\Report\Mapper\Clientes\ListadoNomina;


use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupo;
use App\Event\Event\Novasoft\Report\Mapper\ListadoNominaNuevoObjetoEvent;
use App\Repository\Main\ConvenioRepository;
use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleadoRepository;
use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoRepository;
use App\Service\Novasoft\Report\Exception\InvalidMappedObject;
use App\Service\Novasoft\Report\Mapper\DataFilter;
use App\Service\Novasoft\Report\Mapper\Mapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ListadoNominaMapper extends Mapper
{
    /**
     * @var ListadoNomina
     */
    protected $targetObject;

    /**
     * El mismo ListadoNomina se repite en varios renglones, guardamos para no repetir
     * @var ListadoNomina
     */
    protected $prevTargetObject;

    /**
     * @var ConvenioRepository
     */
    private $convenioRepo;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;


    public function __construct(DataFilter $filter, ConvenioRepository $convenioRepo, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($filter);
        $this->convenioRepo = $convenioRepo;
        $this->dispatcher = $dispatcher;
    }

    protected function instanceTargetObject()
    {
        $targetObject = new ListadoNomina();
        if($this->targetObject){
            $this->prevTargetObject = $this->targetObject;
        }
        return $targetObject;
    }

    protected function defineMap(): array
    {
        return [
            'cod_conv1'=> 'valoresIniciales',
            'tip_liq1' => 'valoresIniciales',
            'fec_cte4' => 'valoresIniciales',
            'empleado' => [
                ListadoNominaEmpleadoMapper::class => [
                    'conv_suc',
                    'conv_suc_des',
                    'cod_cont',
                    'cod_emp',
                    'nom_car',
                    'fec_ing',
                    'sueldo',
                    'por_ate'
                ]
            ],
            'grupo' => [
                ListadoNominaGrupoMapper::class => [
                    'nat_liq2',
                    'textbox907',
                    'nom_con2',
                    'textbox904',
                    'textbox905',
                    'can_liq',
                    'val_liq',
                    'val_liq1',
                    'cod_emp'
                ]
            ]
        ];
    }

    public function setValoresIniciales($value, $csvCol)
    {
        switch ($csvCol) {
            case 'cod_conv1':
                $convenio = $this->convenioRepo->find($value);
                if(!$convenio) {
                    throw new InvalidMappedObject("No existe convenio con codigo '$value'");
                }
                $this->targetObject->setConvenio($convenio);
                break;
            case 'tip_liq1':
                // $value toca extraer el tipo de un texto (ej. "TIPO DE LIQUIDACION: 01 - Liquidación de Nómina")
                if(preg_match('/\D+(\d+).*/', $value, $matches)) {
                    $this->targetObject->setTipoLiquidacion($matches[1]);
                }
                break;
            case 'fec_cte4':
                $fecha = $this->filter->fechaFromNovasoft($value);
                $this->targetObject->setFechaNomina($fecha);

                //ya podemos determinar si targetObject es el mismo que prevObject, para no repetir
                if($this->prevTargetObject) {
                    if($this->targetObject->compare($this->prevTargetObject)) {
                        //convertimos a null, para que no se agregue a la coleccion de objetos mapped
                        $this->targetObject = null;
                    } else {
                        //borra cache de submappers para crear objetos frescos
                        $this->dispatcher->dispatch(new ListadoNominaNuevoObjetoEvent());
                    }
                }
                break;
        }
    }

    /**
     * @param ListadoNominaEmpleado|null $value
     */
    public function setEmpleado($value)
    {
        if($value) {
            $match = $this->getTargetObject()->getEmpleados()->matching(
                ListadoNominaEmpleadoRepository::filterByIdentificacionCriteria($value->getIdentificacion())
            );
            if(!$match->count()) {
                $this->getTargetObject()->addEmpleado($value);
            }
        }
    }

    /**
     * @param ListadoNominaGrupo $value
     */
    public function setGrupo($value)
    {
        $match = $this->getTargetObject()->getGrupos()->matching(ListadoNominaGrupoRepository::filterByNombreCriteria($value->getNombre()));
        if(!$match->count()) {
            $this->getTargetObject()->addGrupo($value);
        }
    }


    public function addMappedObject(&$objects)
    {
        // si no definido, es porque estamos trabajando con uno previamente definido
        if($this->targetObject) {
            $objects[] = $this->targetObject;
        }
        $this->targetObject = $this->instanceTargetObject();
    }

    /**
     * @return ListadoNomina
     */
    public function getTargetObject()
    {
        return $this->targetObject ? $this->targetObject : $this->prevTargetObject;
    }

}