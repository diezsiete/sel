<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaRenglon;
use App\Service\Novasoft\Report\Exception\InvalidMappedObject;
use App\Service\Novasoft\Report\Mapper\Mapper;

class RenglonMapper extends Mapper
{

    /**
     * @var LiquidacionNominaRenglon
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        return new LiquidacionNominaRenglon();
    }

    protected function defineMap(): array
    {
        return [
            'cod_con' => 'concepto',
            'can_liq' => 'unidades',
            'base_sal'=> 'base',
            'devengo' => 'devengos',
            'deduccion' => 'deducciones',
            'cod_cco' => 'centroCosto'
        ];
    }

    public function setConcepto($value)
    {
        preg_match('/(\d+) (.+)/', $value, $matches);
        if(count($matches) > 2) {
            //concepto '999901 Neto a Pagar' es un concepto basura que no se muestra en los reportes pero si sale en el csv
            if($matches[1] === '999901') {
                throw new InvalidMappedObject();
            }
            $this->targetObject
                ->setConceptoCodigo($matches[1])
                ->setConcepto($matches[2]);
        }
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
}