<?php

namespace App\Entity\Napi\Report\ServicioEmpleados;

use App\Annotation\NapiClient\NapiResource;
use App\Annotation\NapiClient\NapiResourceId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @NapiResource(
 *     itemOperations={
 *         "get"={"path"="/certificado-laboral/{empleado}"},
 *     },
 * )
 * @ORM\Entity()
 * @ORM\Table(name="napi_se_certificado_laboral")
 */
class CertificadoLaboral
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=12)
     * @NapiResourceId()
     */
    private $empleado;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $paisExpedicion;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $ciudadExpedicion;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nombreCiudad;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $fechaIngresoTexto;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $tipoContrato;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nombreContrato;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $claSal;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salario;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salarioTexto;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $codigoCargo;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $nombreCargo;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $fechaCertificado;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $tipoId;

    /**
     * @ORM\Column(type="integer")
     */
    private $sexo;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $empresaUsuaria;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $usuario;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * @param mixed $empleado
     * @return CertificadoLaboral
     */
    public function setEmpleado($empleado)
    {
        $this->empleado = $empleado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     * @return CertificadoLaboral
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
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
     * @return CertificadoLaboral
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;
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
     * @return CertificadoLaboral
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     * @return CertificadoLaboral
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaisExpedicion()
    {
        return $this->paisExpedicion;
    }

    /**
     * @param mixed $paisExpedicion
     * @return CertificadoLaboral
     */
    public function setPaisExpedicion($paisExpedicion)
    {
        $this->paisExpedicion = $paisExpedicion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCiudadExpedicion()
    {
        return $this->ciudadExpedicion;
    }

    /**
     * @param mixed $ciudadExpedicion
     * @return CertificadoLaboral
     */
    public function setCiudadExpedicion($ciudadExpedicion)
    {
        $this->ciudadExpedicion = $ciudadExpedicion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreCiudad()
    {
        return $this->nombreCiudad;
    }

    /**
     * @param mixed $nombreCiudad
     * @return CertificadoLaboral
     */
    public function setNombreCiudad($nombreCiudad)
    {
        $this->nombreCiudad = $nombreCiudad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaIngresoTexto()
    {
        return $this->fechaIngresoTexto;
    }

    /**
     * @param mixed $fechaIngresoTexto
     * @return CertificadoLaboral
     */
    public function setFechaIngresoTexto($fechaIngresoTexto)
    {
        $this->fechaIngresoTexto = $fechaIngresoTexto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     * @return CertificadoLaboral
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaRetiro()
    {
        return $this->fechaRetiro;
    }

    /**
     * @param mixed $fechaRetiro
     * @return CertificadoLaboral
     */
    public function setFechaRetiro($fechaRetiro)
    {
        $this->fechaRetiro = $fechaRetiro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoContrato()
    {
        return $this->tipoContrato;
    }

    /**
     * @param mixed $tipoContrato
     * @return CertificadoLaboral
     */
    public function setTipoContrato($tipoContrato)
    {
        $this->tipoContrato = $tipoContrato;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreContrato()
    {
        return $this->nombreContrato;
    }

    /**
     * @param mixed $nombreContrato
     * @return CertificadoLaboral
     */
    public function setNombreContrato($nombreContrato)
    {
        $this->nombreContrato = $nombreContrato;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClaSal()
    {
        return $this->claSal;
    }

    /**
     * @param mixed $claSal
     * @return CertificadoLaboral
     */
    public function setClaSal($claSal)
    {
        $this->claSal = $claSal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     * @return CertificadoLaboral
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * @param mixed $salario
     * @return CertificadoLaboral
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalarioTexto()
    {
        return $this->salarioTexto;
    }

    /**
     * @param mixed $salarioTexto
     * @return CertificadoLaboral
     */
    public function setSalarioTexto($salarioTexto)
    {
        $this->salarioTexto = $salarioTexto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoCargo()
    {
        return $this->codigoCargo;
    }

    /**
     * @param mixed $codigoCargo
     * @return CertificadoLaboral
     */
    public function setCodigoCargo($codigoCargo)
    {
        $this->codigoCargo = $codigoCargo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreCargo()
    {
        return $this->nombreCargo;
    }

    /**
     * @param mixed $nombreCargo
     * @return CertificadoLaboral
     */
    public function setNombreCargo($nombreCargo)
    {
        $this->nombreCargo = $nombreCargo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaCertificado()
    {
        return $this->fechaCertificado;
    }

    /**
     * @param mixed $fechaCertificado
     * @return CertificadoLaboral
     */
    public function setFechaCertificado($fechaCertificado)
    {
        $this->fechaCertificado = $fechaCertificado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * @param mixed $tipoId
     * @return CertificadoLaboral
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @param mixed $sexo
     * @return CertificadoLaboral
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     * @return CertificadoLaboral
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmpresaUsuaria(): ?string
    {
        return $this->empresaUsuaria;
    }

    /**
     * @param string|null $empresaUsuaria
     * @return CertificadoLaboral
     */
    public function setEmpresaUsuaria(?string $empresaUsuaria): CertificadoLaboral
    {
        $this->empresaUsuaria = $empresaUsuaria;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActivo(): bool
    {
        return $this->activo;
    }

    /**
     * @param bool $activo
     * @return CertificadoLaboral
     */
    public function setActivo(bool $activo): CertificadoLaboral
    {
        $this->activo = $activo;
        return $this;
    }

    public function isHombre(): ?bool
    {
        return $this->sexo === 2;
    }

    public function getNombreCompleto(): string
    {
        return $this->primerApellido . ' ' . ($this->segundoApellido ? "$this->segundoApellido " : '') . $this->nombre;
    }
}
