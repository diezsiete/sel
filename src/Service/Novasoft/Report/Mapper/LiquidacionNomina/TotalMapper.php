<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaTotal;
use App\Service\Novasoft\Report\Mapper\Mapper;

class TotalMapper extends Mapper
{
    /**
     * @var LiquidacionNominaTotal
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        return new LiquidacionNominaTotal();
    }

    protected function defineMap(): array
    {
        return [
            'can_liq1' => 'unidades',
            'devengo1' => 'devengos',
            'deduccion1' => 'deducciones',
            'neto1' => 'neto'
        ];
    }

    public function setUnidades($value)
    {
        $this->targetObject->setUnidades($this->filter->int($value));
    }

    public function setDevengos($value)
    {
        $this->targetObject->setDevengos($this->filter->int($value));
    }

    public function setDeducciones($value)
    {
        $this->targetObject->setDeducciones($this->filter->int($value));
    }

    public function setNeto($value)
    {
        $this->targetObject->setNeto($this->filter->int($value));
    }
}