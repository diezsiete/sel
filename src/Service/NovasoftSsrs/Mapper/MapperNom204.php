<?php
/**
 * Maps a record from Report NOMU1503 to Object NovasoftEmpleado
 * User: guerrerojosedario
 * Date: 2018/08/20
 * Time: 11:40 AM
 */

namespace App\Service\NovasoftSsrs\Mapper;


use App\Entity\Novasoft\Report\Nomina\Nomina;
use App\Entity\Novasoft\Report\Nomina\NominaDetalle;
use App\Repository\Main\UsuarioRepository;

class MapperNom204 extends Mapper
{

    /**
     * @var \App\Entity\Novasoft\Report\Nomina\Nomina
     */
    protected $targetObject;

    /**
     * @var \App\Entity\Novasoft\Report\Nomina\Nomina[]
     */
    protected $mappedObjects = [];
    /**
     * @var \App\Repository\Main\UsuarioRepository
     */
    private $usuarioRepository;

    /**
     * MapperNom204 constructor.
     */
    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
        parent::__construct();
    }

    protected function instanceTargetObject()
    {
        return new Nomina();
    }

    protected function defineMap(): array
    {
        return [
            'num_ide' => 'nitTercero',
            'textbox276' => 'convenioCodigoNombre',
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
        $identificacion = preg_replace('/\D/', '', $nitTercero);
        $usuario = $this->usuarioRepository->findByIdentificacionCached($identificacion);
        $this->targetObject->setUsuario($usuario);
    }

    public function setBanco($banco)
    {
        $this->targetObject->setBanco(str_replace('BANCO: ', '', $banco));
    }

    public function setCargo($cargo)
    {
        $this->targetObject->setCargo(str_replace('CARGO :', '', $cargo));
    }

    public function setConvenioCodigoNombre($convenio)
    {
        $this->targetObject->setConvenioCodigoNombre(str_replace('Convenio: ', '', $convenio));
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
            $this->getLastDetalle('devengo')->setCodigo($valor);
        }
    }

    public function setDevengoDetalle($valor)
    {
        if($valor) {
            $this->getLastDetalle('devengo')->setDetalle($valor);
        }
    }

    public function setDevengoCantidad($valor)
    {
        if($valor) {
            $this->getLastDetalle('devengo')->setCantidad($valor);
        }
    }

    public function setDevengados($valor)
    {
        if($valor) {
            $this->getLastDetalle('devengo')->setValor($valor);
        }
    }

    public function setDeducidoCodigo($valor)
    {
        if($valor) {
            $this->getLastDetalle('deducido')->setCodigo($valor);
        }
    }

    public function setDeducidoDetalle($valor)
    {
        if($valor) {
            $this->getLastDetalle('deducido')->setDetalle($valor);
        }
    }

    public function setDeducidoCantidad($valor)
    {
        if($valor) {
            $this->getLastDetalle('deducido')->setCantidad($valor);
        }
    }

    public function setDeducidos($valor)
    {
        if($valor) {
            $this->getLastDetalle('deducido')->setValor($valor);
        }
    }

    /**
     * @param \App\Entity\Novasoft\Report\Nomina\Nomina[] $objects
     */
    public function addMappedObject(&$objects)
    {
        $mappedObject = $this->targetObject;

        $fecha = $mappedObject->getFecha()->format('Ymd');
        if(!isset($objects[$fecha])) {
            $objects[$fecha] = $mappedObject;
        } else {
            $nomina = $objects[$fecha];
            foreach($mappedObject->getDetalles() as $detalle) {
                $nomina->addDetalle($detalle);
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
        $this->targetObject = $this->instanceTargetObject();
    }

    /**
     * @param string $tipo devengo o deducido
     * @return NominaDetalle
     */
    protected function getLastDetalle($tipo = 'devengo')
    {
        $lastDetalle = null;
        $detallesCount = $this->targetObject->getDetalles()->count();
        if($detallesCount) {
            for ($i = $detallesCount - 1; $i >= 0; $i--) {
                $currentDetalle = $this->targetObject->getDetalles()->get($i);
                if($currentDetalle->getTipo() === $tipo) {
                    $lastDetalle = $currentDetalle;
                    break;
                }
            }
        }
        if(!$lastDetalle) {
            $lastDetalle = new NominaDetalle();
            $lastDetalle->setTipo($tipo);
            $this->targetObject->addDetalle($lastDetalle);
        }
        return $lastDetalle;
    }
}