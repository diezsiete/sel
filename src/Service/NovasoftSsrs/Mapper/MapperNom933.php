<?php


namespace App\Service\NovasoftSsrs\Mapper;

use App\Entity\Empleado;
use App\Entity\Usuario;
use App\Service\NovasoftSsrs\DataFilter;

class MapperNom933 extends Mapper
{
    /**
     * @var Empleado
     */
    protected $targetObject;
    /**
     * @var DataFilter
     */
    private $filter;

    protected function instanceTargetObject()
    {
        $targetObject = new Empleado();
        $targetObject->setUsuario(new Usuario());
        return $targetObject;
    }

    public function __construct(DataFilter $filter)
    {
        parent::__construct();
        $this->filter = $filter;
    }

    protected function defineMap(): array
    {
        return [
            'cod_emp' => 'identificacion',
            'ap1_emp' => 'primerApellido',
            'ap2_emp' => 'segundoApellido',
            'nom_emp' => 'nombre',
            'dir_res' => 'direccion',
            'e_mail'  => 'email',
            'fec_egr' => 'fechaRetiro',
            'fec_ing' => 'fechaIngreso',
            'fec_nac' => 'nacimiento',
            'tel_cel' => 'telefono1',
            'tel_res' => 'telefono2',
            'sex_emp' => 'sexo',
            'nom_car' => 'cargo',
            //'est_lab' => 'activo',
            //'nom_con' => 'contrato',
            //'nombre' => 'empresaUsuaria',
            //'nSalario' => 'nsalario',
            //'sal_bas' => 'salario',
            //'fec_ing1' => 'fechaIngresoTextual',
            //'Fcert' => 'fechaCertificadoTextual'

            //'estado_civil' => 'estadoCivil',
            //'hijos' => 'hijos',
            //'ccosto' => 'centroCosto',
            //'nomina' => 'convenio',
        ];
    }

    protected function setIdentificacion($identificacion)
    {
        $this->targetObject->getUsuario()->setIdentificacion($identificacion);
    }

    protected function setPrimerApellido($primerApellido)
    {
        $this->targetObject->getUsuario()->setPrimerApellido($this->filter->limpiarNombre($primerApellido));
    }

    protected function setSegundoApellido($segundoApellido)
    {
        $this->targetObject->getUsuario()->setSegundoApellido($this->filter->limpiarNombre($segundoApellido));
    }

    protected function setNombre($nombre)
    {
        $nombre = $this->filter->limpiarNombre($nombre);
        $nombreSeparado = $this->filter->separarNombre($nombre);
        $this->targetObject->getUsuario()
            ->setPrimerNombre($nombreSeparado[0])
            ->setSegundoNombre($nombreSeparado[1]);
    }

    protected function setEmail($email)
    {
        $this->targetObject->getUsuario()->setEmail($email);
    }

    protected function setNacimiento($nacimiento)
    {
        $this->targetObject->setNacimiento($this->filter->fechaFromNovasoft($nacimiento));
    }

    protected function setFechaIngreso($fechaIngreso)
    {
        $this->targetObject->setFechaIngreso($this->filter->fechaFromNovasoft($fechaIngreso));
    }

    protected function setFechaRetiro($fechaRetiro)
    {
        if($fechaRetiro = $this->filter->fechaFromNovasoft($fechaRetiro)) {
            $this->targetObject->setFechaRetiro($fechaRetiro);
        }
    }
}