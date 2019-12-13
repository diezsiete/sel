<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;
use App\Service\Novasoft\Report\Mapper\Mapper;

class ResumenMapper extends Mapper
{

    /**
     * @var LiquidacionNominaResumen
     */
    protected $targetObject;

    /**
     * @var LiquidacionNominaResumen
     */
    protected $currentResumen;

    protected function instanceTargetObject()
    {
        return new LiquidacionNominaResumen();
    }

    protected function defineMap(): array
    {
        return [
            'renglon' => [
                ResumenRenglonMapper::class => [
                    'cod_con2',
                    'can_liq2',
                    'textbox17',
                    'devengo2',
                    'deduccion2'
                ]
            ],
            'total' => [
                ResumenTotalMapper::class =>[
                    'can_liq3',
                    'devengo3',
                    'deduccion3',
                    'neto3'
                ]
            ]
        ];
    }

    public function setRenglon($value)
    {
        if($value) {
            $this->targetObject->addRenglon($value);
        }
    }

    public function setTotal($value)
    {
        if($value) {
            $this->targetObject->setTotal($value);
        }
    }

    public function addMappedObject(&$objects)
    {
        // para que las filas que no contienen el resumen no retornen objetos vacios
        if($this->targetObject->getRenglones()->count() > 0 && $this->targetObject->getTotal()) {
            if(!$this->currentResumen) {
                $this->currentResumen = $this->targetObject;
                $objects[] = $this->targetObject;
            }
        } else {
            $this->targetObject = $this->instanceTargetObject();
        }
    }
}