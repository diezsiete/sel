<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenTotal;
use App\Service\Novasoft\Report\Mapper\Mapper;

class ResumenTotalMapper extends Mapper
{
    /**
     * @var LiquidacionNominaResumenTotal
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        return new LiquidacionNominaResumenTotal();
    }

    protected function defineMap(): array
    {
        return [
            'can_liq3' => 'unidades',
            'devengo3' => 'devengos',
            'deduccion3' => 'deducciones',
            'neto3' => 'neto'
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

    public function addMappedObject(&$objects)
    {
        //para que filas que no contienen info de resumen no populen con objetos vacios
        if($this->targetObject->getNeto()) {
            $objects[] = $this->targetObject;
        }
        $this->targetObject = $this->instanceTargetObject();
    }
}