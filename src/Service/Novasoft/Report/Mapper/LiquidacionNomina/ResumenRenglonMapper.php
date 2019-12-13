<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenRenglon;
use App\Service\Novasoft\Report\Exception\InvalidMappedObject;
use App\Service\Novasoft\Report\Mapper\Mapper;

class ResumenRenglonMapper extends Mapper
{

    /**
     * @var LiquidacionNominaResumenRenglon
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        return new LiquidacionNominaResumenRenglon();
    }

    protected function defineMap(): array
    {
        return [
            'cod_con2' => 'concepto',
            'can_liq2' => 'unidades',
            'textbox17' => 'base',
            'devengo2' => 'devengos',
            'deduccion2' => 'deducciones',
        ];
    }

    public function setUnidades($value)
    {
        $this->targetObject->setUnidades($this->filter->int($value));
    }

    public function setBase($value)
    {
        $this->targetObject->setBase($this->filter->int($value));
    }

    public function setDevengos($value)
    {
        $this->targetObject->setDevengos($this->filter->int($value));
    }

    public function setDeducciones($value)
    {
        $this->targetObject->setDeducciones($this->filter->int($value));
    }

    public function addMappedObject(&$objects)
    {
        //para que las filas que no contienen el resumen no creen objetos vacios
        //concepto '999901 Neto a Pagar' es un concepto basura que no se muestra en los reportes pero si sale en el csv
        if($this->targetObject->getConcepto() && !preg_match('/^999901.*/', trim($this->targetObject->getConcepto()))) {
            $objects[] = $this->targetObject;
        }
        $this->targetObject = $this->instanceTargetObject();
    }
}