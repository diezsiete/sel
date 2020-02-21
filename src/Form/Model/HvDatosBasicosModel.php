<?php


namespace App\Form\Model;

use App\Entity\Main\Ciudad;
use App\Entity\Main\Dpto;
use App\Entity\Hv\Hv;
use App\Entity\Main\Pais;
use App\Entity\Main\Usuario;
use App\Validator\Hv\Ubicacion;
use App\Validator\IdentificacionUnica;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class HvDatosBasicosModel
 * @package App\Form\Model
 * @IdentificacionUnica(path="identificacion")
 * @Ubicacion()
 */
class HvDatosBasicosModel
{
    public $id;

    /**
     * @Assert\NotBlank(message="Por favor ingrese identificación")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      min = 5,
     *      max = 12,
     *      minMessage = "La identificación debe tener al menos {{ limit }} caracteres",
     *      maxMessage = "La identificación supera el limite de {{ limit }} caracteres"
     * )
     */
    public $identificacion;

    /**
     * @Assert\NotBlank(message="Por favor ingrese correo")
     * @Assert\Email(message="Ingrese un email valido")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "El correo supera el limite de {{ limit }} caracteres"
     * )
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su nombre")
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     */
    private $primerNombre;

    /**
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     */
    private $segundoNombre;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su apellido")
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El apellido supera el limite de {{ limit }} caracteres"
     * )
     */
    private $primerApellido;

    /**
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El apellido supera el limite de {{ limit }} caracteres"
     * )
     */
    private $segundoApellido;


    /*
     * HV---------------------------------------------------------------------------------------------------------------
     */

    /**
     * @var Pais
     * @Assert\NotNull(message="Ingrese pais de nacimiento")
     */
    public $nacPais;

    /**
     * @var Dpto
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
     * @var Dpto
     */
    public $identDpto;

    /**
     * @var Ciudad
     */
    public $identCiudad;

    public $genero;

    public $estadoCivil;

    /**
     * @var Pais
     * @Assert\NotNull(message="Ingrese pais de residencia")
     */
    public $resiPais;

    /**
     * @var Dpto
     */
    public $resiDpto;

    /**
     * @var Ciudad
     */
    public $resiCiudad;

    /**
     * @Assert\NotNull(message="Ingrese barrio")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Barrio supera el limite de {{ limit }} caracteres"
     * )
     */
    public $barrio;

    /**
     * @Assert\NotNull(message="Ingrese dirección")
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "La dirección supera el limite de {{ limit }} caracteres"
     * )
     */
    public $direccion;

    public $grupoSanguineo;

    public $factorRh;

    public $nacionalidad;

    /**
     * @Assert\Email(message="Ingrese un email valido")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Email alternativo supera el limite de {{ limit }} caracteres"
     * )
     */
    public $emailAlt;

    public $aspiracionSueldo;

    public $estatura;

    public $peso;

    public $personasCargo;

    public $identificacionTipo;

    /**
     * @Assert\NotBlank(message="Ingrese su fecha de nacimiento")
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

    /**
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      max = 17,
     *      maxMessage = "Teléfono supera el limite de {{ limit }} caracteres"
     * )
     */
    public $telefono;

    /**
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Teléfono supera el limite de {{ limit }} caracteres"
     * )
     */
    public $celular;

    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = strtoupper($primerNombre);
    }

    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * @return mixed
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * @param mixed $segundoNombre
     * @return HvDatosBasicosModel
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = strtoupper($segundoNombre);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * @param mixed $primerApellido
     * @return HvDatosBasicosModel
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = strtoupper($primerApellido);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * @param mixed $segundoApellido
     * @return HvDatosBasicosModel
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = strtoupper($segundoApellido);
        return $this;
    }

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
     * @param Hv $hv
     * @return Hv
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