<?php

namespace App\Entity\Napi\Report\ServicioEmpleados;

use App\Annotation\NapiClient\NapiResource;
use App\Annotation\NapiClient\NapiResourceId;
use App\Entity\Main\Usuario;
use App\Service\Pdf\ServicioEmpleados\Report\CertificadoIngresosInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @NapiResource(
 *     itemOperations={
 *         "get"={"path"="/se/certificado-ingresos/identificacion={identificacion};fechaInicial={fechaInicial};fechaFinal={fechaFinal}"},
 *     }
 * )
 * @ORM\Entity()
 * @ORM\Table(name="napi_se_certificado_ingresos")
 */
class CertificadoIngresos implements CertificadoIngresosInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @NapiResourceId
     */
    protected $identificacion;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @NapiResourceId
     */
    protected $fechaInicial;

    /**
     * @ORM\Column(type="date")
     * @NapiResourceId
     */
    protected $fechaFinal;

    /**
     * @ORM\Column(type="integer")
     */
    public $numeroFormulario;

    /**
     * @ORM\Column(type="string", length=12)
     */
    protected $nit;

    /**
     * @ORM\Column(type="string", length=1)
     */
    protected $dv;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $razonSocial;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $tipoDocumento;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $primerApellido;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $segundoApellido;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $primerNombre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $segundoNombre;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $fechaExpedicion;

    /**
     * @ORM\Column(type="integer")
     */
    protected $ingresoSalario;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $ciudad;

    /**
     * @ORM\Column(type="string", length=5)
     */
    public $ciudadCodigo;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoHonorarios;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoServicios;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoComisiones;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoPrestaciones;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoViaticos;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoRepresentacion;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoCompensaciones;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoOtros;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoCesantias;

    /**
     * @ORM\Column(type="integer")
     */
    public $ingresoPensiones;

    /**
     * @ORM\Column(type="integer")
     */
    public $aportesSalud;

    /**
     * @ORM\Column(type="integer")
     */
    public $aportesObligatoriosPensiones;

    /**
     * @ORM\Column(type="integer")
     */
    public $aportesVoluntariosPensiones;

    /**
     * @ORM\Column(type="integer")
     */
    public $aportesAfc;

    /**
     * @ORM\Column(type="integer")
     */
    public $valorRetencion;

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
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * @param mixed $identificacion
     * @return CertificadoIngresos
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroFormulario()
    {
        return $this->numeroFormulario;
    }

    /**
     * @param mixed $numeroFormulario
     * @return CertificadoIngresos
     */
    public function setNumeroFormulario($numeroFormulario)
    {
        $this->numeroFormulario = $numeroFormulario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNit(): string
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     * @return CertificadoIngresos
     */
    public function setNit($nit)
    {
        $this->nit = $nit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaInicial(): DateTimeInterface
    {
        return $this->fechaInicial ?: new DateTime($this->fechaFinal->format('Y') . '-01-01');
    }

    /**
     * @param mixed $fechaInicial
     * @return CertificadoIngresos
     */
    public function setFechaInicial($fechaInicial)
    {
        $this->fechaInicial = $fechaInicial;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaFinal(): DateTimeInterface
    {
        return $this->fechaFinal;
    }

    /**
     * @param mixed $fechaFinal
     * @return CertificadoIngresos
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDv()
    {
        return $this->dv;
    }

    /**
     * @param mixed $dv
     * @return CertificadoIngresos
     */
    public function setDv($dv)
    {
        $this->dv = $dv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRazonSocial(): string
    {
        return $this->razonSocial;
    }

    /**
     * @param mixed $razonSocial
     * @return CertificadoIngresos
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = $razonSocial;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * @param mixed $tipoDocumento
     * @return CertificadoIngresos
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;
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
     * @return CertificadoIngresos
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
     * @return CertificadoIngresos
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * @param mixed $primerNombre
     * @return CertificadoIngresos
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;
        return $this;
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
     * @return CertificadoIngresos
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaExpedicion(): DateTimeInterface
    {
        return $this->fechaExpedicion;
    }

    /**
     * @param mixed $fechaExpedicion
     * @return CertificadoIngresos
     */
    public function setFechaExpedicion($fechaExpedicion)
    {
        $this->fechaExpedicion = $fechaExpedicion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIngresoSalario()
    {
        return $this->ingresoSalario;
    }

    public function getIngresoSalarioFormat(): string
    {
        return (string)number_format($this->ingresoSalario);
    }

    /**
     * @param mixed $ingresoSalario
     * @return CertificadoIngresos
     */
    public function setIngresoSalario($ingresoSalario)
    {
        $this->ingresoSalario = $ingresoSalario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * @param mixed $ciudad
     * @return CertificadoIngresos
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCiudadCodigo()
    {
        return substr($this->ciudadCodigo, 2);
    }

    /**
     * @param mixed $ciudadCodigo
     * @return CertificadoIngresos
     */
    public function setCiudadCodigo($ciudadCodigo)
    {
        $this->ciudadCodigo = $ciudadCodigo;
        return $this;
    }

    public function getDepartamentoCodigo()
    {
        return substr($this->ciudadCodigo, 0, 2);
    }

    /**
     * @param mixed $ingresoHonorarios
     * @return CertificadoIngresos
     */
    public function setIngresoHonorarios($ingresoHonorarios)
    {
        $this->ingresoHonorarios = $ingresoHonorarios;
        return $this;
    }

    /**
     * @param mixed $ingresoServicios
     * @return CertificadoIngresos
     */
    public function setIngresoServicios($ingresoServicios)
    {
        $this->ingresoServicios = $ingresoServicios;
        return $this;
    }

    /**
     * @param mixed $ingresoComisiones
     * @return CertificadoIngresos
     */
    public function setIngresoComisiones($ingresoComisiones)
    {
        $this->ingresoComisiones = $ingresoComisiones;
        return $this;
    }

    /**
     * @param mixed $ingresoPrestaciones
     * @return CertificadoIngresos
     */
    public function setIngresoPrestaciones($ingresoPrestaciones)
    {
        $this->ingresoPrestaciones = $ingresoPrestaciones;
        return $this;
    }

    /**
     * @param mixed $ingresoViaticos
     * @return CertificadoIngresos
     */
    public function setIngresoViaticos($ingresoViaticos)
    {
        $this->ingresoViaticos = $ingresoViaticos;
        return $this;
    }

    /**
     * @param mixed $ingresoRepresentacion
     * @return CertificadoIngresos
     */
    public function setIngresoRepresentacion($ingresoRepresentacion)
    {
        $this->ingresoRepresentacion = $ingresoRepresentacion;
        return $this;
    }

    /**
     * @param mixed $ingresoCompensaciones
     * @return CertificadoIngresos
     */
    public function setIngresoCompensaciones($ingresoCompensaciones)
    {
        $this->ingresoCompensaciones = $ingresoCompensaciones;
        return $this;
    }

    /**
     * @param mixed $ingresoOtros
     * @return CertificadoIngresos
     */
    public function setIngresoOtros($ingresoOtros)
    {
        $this->ingresoOtros = $ingresoOtros;
        return $this;
    }

    /**
     * @param mixed $ingresoCesantias
     * @return CertificadoIngresos
     */
    public function setIngresoCesantias($ingresoCesantias)
    {
        $this->ingresoCesantias = $ingresoCesantias;
        return $this;
    }

    /**
     * @param mixed $ingresoPensiones
     * @return CertificadoIngresos
     */
    public function setIngresoPensiones($ingresoPensiones)
    {
        $this->ingresoPensiones = $ingresoPensiones;
        return $this;
    }

    /**
     * @return Usuario|null
     */
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario|null $usuario
     * @return $this
     */
    public function setUsuario(?Usuario $usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getMonto($property)
    {
        return $this->$property;
    }

    public function getIngresoProperties(): array
    {
        return [
            'ingresoSalario', 'ingresoHonorarios', 'ingresoServicios', 'ingresoComisiones', 'ingresoPrestaciones',
            'ingresoViaticos', 'ingresoRepresentacion', 'ingresoCompensaciones', 'ingresoOtros', 'ingresoCesantias', 'ingresoPensiones'
        ];
    }

    public function getAportesProperties(): array
    {
        return ['aportesSalud','aportesObligatoriosPensiones','aportesVoluntariosPensiones','aportesAfc','valorRetencion'];
    }

    public function getIngresoTotal()
    {
        return array_reduce($this->getIngresoProperties(), function ($carrier, $property) {
            return $carrier + $this->$property;
        }, 0);
    }
}