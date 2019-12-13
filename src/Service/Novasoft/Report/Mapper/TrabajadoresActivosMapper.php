<?php


namespace App\Service\Novasoft\Report\Mapper;


use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\Novasoft\Report\CentroCosto;
use App\Entity\Novasoft\Report\TrabajadorActivo;
use App\Entity\Usuario;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use App\Repository\Novasoft\Report\CentroCostoRepository;

class TrabajadoresActivosMapper extends Mapper
{
    /**
     * @var TrabajadorActivo
     */
    protected $targetObject;

    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;

    /**
     * @var array
     */
    private $currentConvenio = [];

    /**
     * @var Convenio[]
     */
    private $convenios = [];

    /**
     * @var array
     */
    private $currentCentroCosto = null;

    /**
     * @var CentroCosto[]
     */
    private $centroCostos = [];

    /**
     * @var CentroCostoRepository
     */
    private $centroCostoRepository;

    public function __construct(DataFilter $filter, EmpleadoRepository $empleadoRepository,
                                ConvenioRepository $convenioRepository, CentroCostoRepository $centroCostoRepository)
    {
        parent::__construct($filter);
        $this->empleadoRepository = $empleadoRepository;
        $this->convenioRepository = $convenioRepository;
        $this->centroCostoRepository = $centroCostoRepository;
    }

    protected function instanceTargetObject()
    {
        return new TrabajadorActivo();
    }

    protected function defineMap(): array
    {
        return [
            'cod_conv' => 'convenio',
            'convenio1' => 'convenio',
            'cod_conv2' => 'centroCosto',
            'convenio2' => 'centroCosto',
            'num_ide1' => 'empleadoIdentificacion',
            'textbox30' => 'empleadoNombre',
            'fec_ing1' => 'fechaIngreso',
            'sal_bas' => 'salario',
            'textbox34' => 'labor',
            'fec_fin1' => 'fechaRetiro',
            'por_ate' => 'porcentajeRiesgo',
            'textbox37' => 'cuenta',
            'textbox38' => 'caja',
            'textbox39' => 'promotoraSalud',
            'textbox40' => 'adminPension'
        ];
    }

    public function setConvenio($value, $originalName)
    {
        $this->currentConvenio[$originalName] = $originalName === 'cod_conv'
            ? trim(preg_replace('/^.+Negocio:(.+)/', '$1', $value))
            : trim($value);

        if(count($this->currentConvenio) === 2) {
            $convenio = new Convenio();
            foreach($this->currentConvenio as $convenioOriginalName => $value) {
                $convenioOriginalName === 'cod_conv' ? $convenio->setCodigo($value) : $convenio->setNombre($value);
            }
            if(!isset($this->convenios[$convenio->getCodigo()])) {
                $databaseConvenio = $this->convenioRepository->find($convenio->getCodigo());
                if($databaseConvenio) {
                    $convenio = $databaseConvenio;
                }
                $this->convenios[$convenio->getCodigo()] = $convenio;
            }

            $this->targetObject->setConvenio($this->convenios[$convenio->getCodigo()]);

            $this->currentConvenio = [];
        }
    }

    public function setCentroCosto($value, $originalName)
    {
        $this->currentCentroCosto[$originalName] = $originalName === 'cod_conv2'
            ? trim(str_replace('Centro Costo:', '', $value))
            : trim($value);


        if(count($this->currentCentroCosto) === 2) {
            $centroCosto = new CentroCosto();
            foreach($this->currentCentroCosto as $centroCostoOriginalName => $value) {
                $centroCostoOriginalName === 'cod_conv2' ? $centroCosto->setCodigo($value) : $centroCosto->setNombre($value);
            }
            if(!isset($this->centroCostos[$centroCosto->getCodigo()])) {
                $this->centroCostos[$centroCosto->getCodigo()] = $centroCosto;
            }

            $this->targetObject->setCentroCosto($this->centroCostos[$centroCosto->getCodigo()]);

            $this->currentCentroCosto = [];
        }
    }

    public function setEmpleadoIdentificacion($value)
    {
        if(!$this->targetObject->getEmpleado()) {
            $empleado = $this->empleadoRepository->findByIdentificacion($value);
            if(!$empleado) {
                $empleado = (new Empleado())->setUsuario((new Usuario())->setIdentificacion($value));
            }
            $this->targetObject->setEmpleado($empleado);
        }
    }

    public function setEmpleadoNombre($value)
    {
        $nombreCompleto = $this->filter->separarNombreCompleto($value);
        $this->targetObject->getEmpleado()->getUsuario()
            ->setPrimerNombre($nombreCompleto[0])
            ->setSegundoNombre($nombreCompleto[1])
            ->setPrimerApellido($nombreCompleto[2])
            ->setSegundoApellido($nombreCompleto[3]);
    }

    public function setFechaIngreso($value)
    {
        $fechaIngreso = $this->filter->fechaFromNovasoft($value);
        $this->targetObject->setFechaIngreso($fechaIngreso);
        $this->targetObject->getEmpleado()->setFechaIngreso($fechaIngreso);
    }

    public function setSalario($value)
    {
        $ingresoBasico = $this->filter->int($value);
        $this->targetObject->setIngresoBasico($ingresoBasico);
    }

    public function setFechaRetiro($value)
    {
        $fecha = $this->filter->fechaFromNovasoft($value);
        $this->targetObject->setFechaRetiro($fecha);
        $this->targetObject->getEmpleado()->setFechaRetiro($fecha);
    }

    public function setPorcentajeRiesgo($value)
    {
        $this->targetObject->setPorcentajeRiesgo($this->filter->float($value));
    }
}