<?php


namespace App\Form\Model;

use App\Entity\Main\Ciudad;
use App\Entity\Main\Dpto;
use App\Entity\Hv\Hv;
use App\Entity\Main\Pais;
use App\Entity\Main\Usuario;
use App\Validator\IdentificacionUnica;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class HvDatosBasicosModel
 * @package App\Form\Model
 * @IdentificacionUnica(path="identificacion")
 */
class HvDatosBasicosModel
{
    public $id;

    /**
     * @Assert\NotBlank(message="Por favor ingrese identificación")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     */
    public $identificacion;

    /**
     * @Assert\NotBlank(message="Por favor ingrese correo")
     * @Assert\Email(message="Ingrese un email valido")
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su nombre")
     */
    public $primerNombre;


    public $segundoNombre;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su apellido")
     */
    public $primerApellido;
    
    public $segundoApellido;


    /*
     * HV---------------------------------------------------------------------------------------------------------------
     */

    /**
     * @var Pais
     * @Assert\NotNull(message="Ingrese pais de nacimiento")
     */
    public $nacPais;

    /**
     * @var \App\Entity\Main\Dpto
     */
    public $nacDpto;

    /**
     * @var Ciudad
     */
    public $nacCiudad;

    /**
     * @var Pais
     * @Assert\NotNull(message="Ingrese pais de identificación")
     */
    public $identPais;

    /**
     * @var \App\Entity\Main\Dpto
     */
    public $identDpto;

    /**
     * @var \App\Entity\Main\Ciudad
     */
    public $identCiudad;

    public $genero;

    public $estadoCivil;

    /**
     * @var \App\Entity\Main\Pais
     * @Assert\NotNull(message="Ingrese pais de residencia")
     */
    public $resiPais;

    /**
     * @var Dpto
     */
    public $resiDpto;

    /**
     * @var \App\Entity\Main\Ciudad
     */
    public $resiCiudad;

    /**
     * @Assert\NotNull(message="Ingrese barrio")
     */
    public $barrio;

    /**
     * @Assert\NotNull(message="Ingrese dirección")
     */
    public $direccion;

    public $grupoSanguineo;

    public $factorRh;

    public $nacionalidad;

    /**
     * @Assert\Email(message="Ingrese un email valido")
     */
    public $emailAlt;

    public $aspiracionSueldo;

    public $estatura;

    public $peso;

    public $personasCargo;

    public $identificacionTipo;

    /**
     * @Assert\NotBlank(message="Ingrese su fecha de nacimiento")
     * @Assert\Date(message="Ingrese fecha valida de nacimiento")
     */
    public $nacimiento;

    public $lmilitarClase;

    public $lmilitarNumero;

    public $lmilitarDistrito;

    public $presupuestoMensual;

    public $deudas;

    public $deudasConcepto;
    
    /**
     * @Assert\NotNull(message="Ingrese nivel academico")
     */
    public $nivelAcademico;
    
    public $telefono;
    
    public $celular;


    public function getNacPais(): ?Pais
    {
        return $this->nacPais;
    }

    public function getNacDpto(): ?Dpto
    {
        return $this->nacDpto;
    }

    public function getIdentPais(): ?Pais
    {
        return $this->identPais;
    }

    public function getIdentDpto(): ?Dpto
    {
        return $this->identDpto;
    }

    public function getResiPais(): ?Pais
    {
        return $this->resiPais;
    }

    public function getResiDpto(): ?Dpto
    {
        return $this->resiDpto;
    }

    public function fillFromEntities(?Usuario $usuario, ?Hv $hv)
    {
        if($usuario) {
            $properties = get_object_vars($this);
            $this->assignFromEntity($properties, $usuario);
        }
        if($hv) {
            $properties = array_reverse(get_object_vars($this));
            $this->assignFromEntity($properties, $hv);
        }
        return $this;
    }

    /**
     * @param \App\Entity\Hv\Hv $hv
     * @return \App\Entity\Hv\Hv
     */
    public function fillHv(Hv $hv)
    {
        $properties = array_reverse(get_object_vars($this));
        return $this->assignToEntity($properties, $hv);
    }

    /**
     * @param Usuario $usuario
     * @return Usuario
     */
    public function fillUsuario(Usuario $usuario)
    {
        $properties = get_object_vars($this);
        unset($properties['id']);
        return $this->assignToEntity($properties, $usuario);
    }

    private function assignFromEntity($properties, $entity)
    {
        foreach($properties as $propertyName => $propertyValue) {
            $methodName = "get" . ucfirst($propertyName);
            if(method_exists($entity, $methodName)) {
                $this->$propertyName = $entity->$methodName();
            } else {
                break;
            }
        }
    }

    private function assignToEntity($properties, $entity)
    {
        foreach($properties as $propertyName => $propertyValue) {
            $methodName = "set" . ucfirst($propertyName);
            if(method_exists($entity, $methodName)) {
                $entity->$methodName($this->$propertyName);
            } else {
                break;
            }
        }
        return $entity;
    }


}