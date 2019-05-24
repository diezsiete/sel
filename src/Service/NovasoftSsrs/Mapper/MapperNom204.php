<?php
/**
 * Maps a record from Report NOMU1503 to Object NovasoftEmpleado
 * User: guerrerojosedario
 * Date: 2018/08/20
 * Time: 11:40 AM
 */

namespace App\Service\NovasoftSsrs\Mapper;



use App\Service\NovasoftSsrs\Entity\NovasoftNomina;
use App\Service\NovasoftSsrs\Entity\NovasoftNominaDeducido;
use App\Service\NovasoftSsrs\Entity\NovasoftNominaDevengo;

class MapperNom204 extends Mapper
{
    /**
     * @var NovasoftNomina
     */
    protected $targetObject;

    /**
     * @var NovasoftNomina[]
     */
    protected $mappedObjects = [];

    protected function defineTargetClass()
    {
        $this->targetClass = NovasoftNomina::class;
    }

    protected function defineMap()
    {
        $this->map = [
            'textbox12' => 'nombre',
            'num_ide' => 'nitTercero',
            'textbox276' => 'convenioNombre',
            'textbox22' => 'fecha',
            'textbox2' => 'pension',
            'textbox3' => 'salud',
            'textbox1' => 'banco',
            'textbox5' => 'cuenta',
            'salario' => 'salario',
            'textbox10' => 'cargo',
            'con_dev' => 'devengoCodigo',
            'nom_dev' => 'devengoDetalle',
            'can_dev' => 'devengoCantidad',
            'devengados' => 'devengados',
            'con_ded' => 'deducidoCodigo',
            'nom_ded' => 'deducidoDetalle',
            'can_dev_1' => 'deducidoCantidad',
            'deducidos' => 'deducidos',
            'devengados_1' => 'devengadosTotal',
            'deducidos_1' => 'deducidosTotal',
            'neto' => 'neto',
            'monto' => 'netoTexto',
            'bas_sal' => 'baseSalario',
            'bas_pen' => 'basePension',
            'bas_ret' => 'baseRetencion',
            'met_ret' => 'metRetencion',
            'met_ret2' => 'porcentajeRetencion',
            'met_ret3' => 'diasVacacionesPend',
        ];
    }

    public function setNitTercero($nitTercero)
    {
        $this->targetObject->setNitTercero(preg_replace('/\D/', '', $nitTercero));
    }

    public function setBanco($banco)
    {
        $this->targetObject->setBanco(str_replace('BANCO: ', '', $banco));
    }

    public function setCargo($cargo)
    {
        $this->targetObject->setCargo(str_replace('CARGO :', '', $cargo));
    }

    public function setConvenioNombre($convenio)
    {
        $this->targetObject->setConvenioNombre(str_replace('Convenio: ', '', $convenio));
    }

    public function setCuenta($cuenta)
    {
        $this->targetObject->setCuenta(str_replace('CUENTA :', '', $cuenta));
    }

    public function setFecha($fecha)
    {
        $fecha = str_replace('NOMINA A : ', '', $fecha);
        $this->targetObject->setFecha(\DateTime::createFromFormat('d/m/Y', $fecha));
    }

    public function setPension($pension)
    {
        $this->targetObject->setPension(str_replace('PENSION:', '', $pension));
    }

    public function setSalud($salud)
    {
        $this->targetObject->setSalud(str_replace('SALUD:', '', $salud));
    }

    public function setDevengoCodigo($valor)
    {
        if($valor) {
            $this->getLastDevengo()->setCodigo($valor);
        }
    }

    public function setDevengoDetalle($valor)
    {
        if($valor) {
            $this->getLastDevengo()->setDetalle($valor);
        }
    }

    public function setDevengoCantidad($valor)
    {
        if($valor) {
            $this->getLastDevengo()->setCantidad($valor);
        }
    }

    public function setDevengados($valor)
    {
        if($valor) {
            $this->getLastDevengo()->setDevengados($valor);
        }
    }

    public function setDeducidoCodigo($valor)
    {
        if($valor) {
            $this->getLastDeducido()->setCodigo($valor);
        }
    }

    public function setDeducidoDetalle($valor)
    {
        if($valor) {
            $this->getLastDeducido()->setDetalle($valor);
        }
    }

    public function setDeducidoCantidad($valor)
    {
        if($valor) {
            $this->getLastDeducido()->setCantidad($valor);
        }
    }

    public function setDeducidos($valor)
    {
        if($valor) {
            $this->getLastDeducido()->setDeducidos($valor);
        }
    }

    /**
     * @param NovasoftNomina[] $objects
     */
    public function addMappedObject(&$objects)
    {
        $mappedObject = $this->targetObject;
        $fecha = $mappedObject->getFecha()->format('Ymd');
        if(!isset($objects[$fecha])) {
            $objects[$fecha] = $mappedObject;
        } else {
            $nomina = $objects[$fecha];
            foreach($mappedObject->getDeducidos() as $deducido) {
                $nomina->addDeducido($deducido);
            }
            foreach($mappedObject->getDevengados() as $devengo) {
                $nomina->addDevengo($devengo);
            }
            if($mappedObject->getBaseSalario()) {
                $nomina->setBaseSalario($mappedObject->getBaseSalario());
            }
            if($mappedObject->getBasePension()) {
                $nomina->setBasePension($mappedObject->getBasePension());
            }
            if($mappedObject->getBaseRetencion()) {
                $nomina->setBaseRetencion($mappedObject->getBaseRetencion());
            }
            if($mappedObject->getMetRetencion()) {
                $nomina->setMetRetencion($mappedObject->getMetRetencion());
            }
            if($mappedObject->getPorcentajeRetencion()) {
                $nomina->setPorcentajeRetencion($mappedObject->getPorcentajeRetencion());
            }
            if($mappedObject->getDiasVacacionesPend()) {
                $nomina->setDiasVacacionesPend($mappedObject->getDiasVacacionesPend());
            }
        }
        $this->targetObject = new $this->targetClass();
    }

    /**
     * @return NovasoftNominaDevengo
     */
    protected function getLastDevengo()
    {
        $devengo = $this->targetObject->getDevengados()->last();
        if(!$devengo) {
            $devengo = new NovasoftNominaDevengo();
            $this->targetObject->addDevengo($devengo);
        }
        return $devengo;
    }

    /**
     * @return NovasoftNominaDeducido
     */
    protected function getLastDeducido()
    {
        $deducido = $this->targetObject->getDeducidos()->last();
        if(!$deducido) {
            $deducido = new NovasoftNominaDeducido();
            $this->targetObject->addDeducido($deducido);
        }
        return $deducido;
    }

}