<?php


namespace App\Service\Novasoft\Report\Mapper\Clientes\ListadoNomina;


use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoTotal;
use App\Service\Novasoft\Report\Mapper\Mapper;


class ListadoNominaGrupoTotalMapper extends Mapper
{
    /**
     * @var ListadoNominaGrupoTotal
     */
    protected $targetObject;


    protected function instanceTargetObject()
    {
        return new ListadoNominaGrupoTotal();
    }

    protected function defineMap(): array
    {
        return [
            'empleado' => [ListadoNominaEmpleadoMapper::class => [
                'cod_emp'
            ]],
            'val_liq1' => 'valor',
        ];
    }

    public function setValor($value)
    {
        $this->targetObject->setValor($this->filter->int($value));
    }

    /**
     * @param ListadoNominaEmpleado $value
     */
    public function setEmpleado($value)
    {
        $this->targetObject
            ->setEmpleado($value)
            ->setIdentificacion($value->getIdentificacion());

    }
}