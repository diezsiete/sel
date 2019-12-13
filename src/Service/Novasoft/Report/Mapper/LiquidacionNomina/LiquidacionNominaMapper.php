<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Empleado;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina;
use App\Service\Novasoft\Report\Mapper\Mapper;

class LiquidacionNominaMapper extends Mapper
{
    /**
     * @var LiquidacionNomina
     */
    protected $targetObject;

    /**
     * @var LiquidacionNomina[]
     */
    protected $liquidacionesNomina = [];

    protected $currentLiquidacionNomina = null;


    protected function instanceTargetObject()
    {
        return new LiquidacionNomina();
    }

    protected function defineMap(): array
    {
        return [
            'empleado' => [
                EmpleadoMapper::class => [
                    'num_ide',
                    'num_ide2'
                ]
            ],
            'ffecini' => 'fechaInicial',
            'ffecfin' => 'fechaFinal',
            'sueldo' => 'ingresoBasico',
            'convenio' => [
                ConvenioMapper::class => [
                    'textbox4',
                    'textbox5'
                ]
            ],
            'num_ide3' => 'fechaIngreso',
            'num_ide4' => 'cargoCodigo',
            'num_ide6' => 'cargo',
            'num_ide5' => 'cuenta',
            'renglon' => [
                RenglonMapper::class => [
                    'cod_con',
                    'can_liq',
                    'base_sal',
                    'devengo',
                    'deduccion',
                    'cod_cco'
                ]
            ],
            'total' => [
                TotalMapper::class => [
                    'can_liq1',
                    'devengo1',
                    'deduccion1',
                    'neto1'
                ]
            ],
            'resumen' => [
                ResumenMapper::class => [
                    'cod_con2',
                    'can_liq2',
                    'textbox17',
                    'devengo2',
                    'deduccion2',
                    'can_liq3',
                    'devengo3',
                    'deduccion3',
                    'neto3'
                ]
            ]
        ];
    }

    /**
     * @param Empleado $value
     */
    public function setEmpleado($value)
    {
        $identificacion = $value->getUsuario()->getIdentificacion();
        if($identificacion && isset($this->liquidacionesNomina[$identificacion])) {
            $this->targetObject = null;
            $this->currentLiquidacionNomina = $this->liquidacionesNomina[$value->getUsuario()->getIdentificacion()];
        } else {
            $this->targetObject->setEmpleado($value);
        }
    }

    public function setFechaInicial($value)
    {
        $fecha = $this->filter->fechaFromNovasoft($value);
        $this->getTargetObject()->setFechaInicial($fecha);
    }


    public function setFechaFinal($value)
    {
        $fecha = $this->filter->fechaFromNovasoft($value);
        $this->getTargetObject()->setFechaFinal($fecha);
    }

    public function setIngresoBasico($value)
    {
        $this->getTargetObject()->setIngresoBasico($this->filter->int($value));
    }

    public function setFechaIngreso($value)
    {
        $this->getTargetObject()->setFechaIngreso($this->filter->fechaFromNovasoft($value));
    }

    public function setRenglon($value)
    {
        //puede venir renglon vacio (ej. renglon basura '999901 Neto a Pagar'
        if($value) {
            $this->getTargetObject()->addRenglon($value);
        }
    }

    public function setResumen($value)
    {
        if($value) {
            $this->getTargetObject()->setResumen($value);
        }
    }

    public function addMappedObject(&$objects)
    {
        if($this->targetObject) {
            if(!$this->targetObject->getResumen()) {
                if($this->targetObject->getEmpleado()->getUsuario()->getIdentificacion()) {
                    $objects[] = $this->targetObject;
                    $this->liquidacionesNomina[$this->targetObject->getEmpleado()->getUsuario()->getIdentificacion()] = $this->targetObject;
                }
            }
            //LiquidacionNomina con resumen es un objeto vacio que solo contiene el resumen, asignamos a los reales
            else {
                foreach($this->liquidacionesNomina as $liquidacionNomina) {
                    $liquidacionNomina->setResumen($this->targetObject->getResumen());
                }
            }
        }
        $this->targetObject = $this->instanceTargetObject();
        $this->currentLiquidacionNomina = null;
    }

    public function getTargetObject()
    {
        return $this->currentLiquidacionNomina ? $this->currentLiquidacionNomina : $this->targetObject;
    }
}