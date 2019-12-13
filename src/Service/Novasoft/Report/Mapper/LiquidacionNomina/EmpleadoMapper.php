<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Empleado;
use App\Entity\Usuario;
use App\Repository\EmpleadoRepository;
use App\Service\Novasoft\Report\Mapper\DataFilter;
use App\Service\Novasoft\Report\Mapper\Mapper;

class EmpleadoMapper extends Mapper
{
    /**
     * @var Empleado
     */
    protected $targetObject;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;

    public function __construct(DataFilter $filter, EmpleadoRepository $empleadoRepository)
    {
        parent::__construct($filter);
        $this->empleadoRepository = $empleadoRepository;
    }

    protected function instanceTargetObject()
    {
        $targetObject = new Empleado();
        $targetObject->setUsuario(new Usuario());
        return $targetObject;
    }

    protected function defineMap(): array
    {
        return [
            'num_ide' => 'identificacion',
            'num_ide2' => 'nombres'
        ];
    }

    public function setIdentificacion($value)
    {
        $this->targetObject->getUsuario()->setIdentificacion($value);
    }

    public function setNombres($value)
    {
        $nombreCompleto = $this->filter->separarNombreCompleto($value);
        $this->targetObject->getUsuario()
            ->setPrimerNombre($nombreCompleto[0])
            ->setSegundoNombre($nombreCompleto[1])
            ->setPrimerApellido($nombreCompleto[2])
            ->setSegundoApellido($nombreCompleto[3]);
    }

    public function addMappedObject(&$objects)
    {
        $empleadoDb = $this->empleadoRepository->findByIdentificacion($this->targetObject->getUsuario()->getIdentificacion());
        if($empleadoDb) {
            $this->targetObject = $empleadoDb;
        }
        parent::addMappedObject($objects);
    }
}