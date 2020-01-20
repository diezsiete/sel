<?php


namespace App\Service\Novasoft\Report\Mapper\Clientes\ListadoNomina;


use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaRenglon;
use App\Service\Novasoft\Report\Mapper\Mapper;

class ListadoNominaRenglonMapper extends Mapper
{
    /**
     * @var ListadoNominaRenglon
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        return new ListadoNominaRenglon();
    }

    protected function defineMap(): array
    {
        return [
            'can_liq' => 'cantidad',
            'val_liq' => 'valor',
            'empleado' => [ListadoNominaEmpleadoMapper::class => [
                'cod_emp'
            ]]
        ];
    }

    public function setCantidad($valor)
    {
        $this->targetObject->setCantidad($this->filter->float($valor));
    }

    public function setValor($valor)
    {
        $this->targetObject->setValor($this->filter->int($valor));
    }

    /**
     * @param ListadoNominaEmpleado $empleado
     */
    public function setEmpleado($empleado)
    {
        $this->targetObject->setEmpleado($empleado);
    }
}