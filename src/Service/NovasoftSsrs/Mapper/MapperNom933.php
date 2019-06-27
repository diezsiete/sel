<?php


namespace App\Service\NovasoftSsrs\Mapper;

use App\Entity\Empleado;
use App\Entity\Usuario;
use App\Service\NovasoftSsrs\Exception\InvalidMappedObject;

class MapperNom933 extends Mapper
{
    /**
     * @var Empleado
     */
    protected $targetObject;


    protected function instanceTargetObject()
    {
        $targetObject = new Empleado();
        $targetObject->setUsuario(new Usuario());
        return $targetObject;
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
        ];
    }

    protected function setIdentificacion($identificacion)
    {
        if(!$identificacion) {
            throw new InvalidMappedObject();
        }
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
        $email = $this->filter->emailValido($email);
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
        if($fechaRetiro) {
            $this->targetObject->setFechaRetiro($this->filter->fechaFromNovasoft($fechaRetiro));
        }
    }
}