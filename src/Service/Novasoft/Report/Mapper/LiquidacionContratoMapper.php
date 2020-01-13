<?php


namespace App\Service\Novasoft\Report\Mapper;


use App\Entity\Novasoft\Report\LiquidacionContrato;
use App\Service\NovasoftSsrs\Entity\ReporteLiquidacion;
use DateTime;

class LiquidacionContratoMapper extends Mapper
{
    /**
     * @var ReporteLiquidacion
     */
    protected $targetObject;


    protected function instanceTargetObject()
    {
        return new LiquidacionContrato();
    }

    protected function defineMap(): array
    {
        return [
            'cod_emp' => 'identificacion',
            'nomemp' => 'nombreCompleto',
            'cod_suc' => 'codigoSucursal',
            'nom_suc' => 'nombreSucursal',
            'cod_cco' => 'centroCosto',
            'nom_cco' => 'nombreCentroCosto',
            'per_ini' => 'regimenCesantias',
            'nom_con_1' => 'tipoContrato',
            'cod_cont' => 'numeroContrato',
            'nom_car_1' => 'ultimoCargo',
            'textbox25' => 'pension',
            'nom_ret2' => 'causaTerminacionContrato',
            'textbox50' => 'salud',
            'fec_ing_1' => 'fechaIngreso',
            'textbox379' => 'fechaRetiro',
            'diatot' => 'diasTotales',
            'diaint' => 'diasLicencia',
            'textbox43' => 'ultimoSueldo',
            'basces' => 'baseCesantias',
            'basprim' => 'basePrima',
            'basvac' => 'baseVacaciones'
        ];
    }

    public function setFechaIngreso($fecha)
    {
        $this->targetObject->setFechaIngreso(DateTime::createFromFormat('d/m/Y', $fecha));
    }

    public function setFechaRetiro($fecha)
    {
        $this->targetObject->setFechaRetiro(DateTime::createFromFormat('d/m/Y', $fecha));
    }

    /**
     * @param ReporteLiquidacion[] $objects
     */
    public function addMappedObject(&$objects)
    {
        $mappedObject = $this->targetObject;
        $fecha = $mappedObject->getFechaIngreso()->format('Ymd');
        if(!isset($objects[$fecha])) {
            $objects[$fecha] = $mappedObject;
        } else {
            $liquidacion = $objects[$fecha];
            if($mappedObject->getBaseCesantias()) {
                $liquidacion->setBaseCesantias($mappedObject->getBaseCesantias());
            }
            if($mappedObject->getBasePrima()) {
                $liquidacion->setBasePrima($mappedObject->getBasePrima());
            }
            if($mappedObject->getBaseVacaciones()) {
                $liquidacion->setBaseVacaciones($mappedObject->getBaseVacaciones());
            }
        }
        $this->targetObject = new $this->targetObject();
    }
}