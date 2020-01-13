<?php

namespace App\Entity\Novasoft\Report;

use App\Entity\Main\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\CertificadoIngresosRepository")
 * @ORM\Table(name="novasoft_certificado_ingresos")
 */
class CertificadoIngresos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $DV;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nombres;

    /**
     * @ORM\Column(type="date")
     */
    private $periodoCertificacionDe;

    /**
     * @ORM\Column(type="date")
     */
    private $periodoCertificacionA;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaExpedicion;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $lugarRetencion;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $codigoDepartamento = "";

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $codigoCiudad = "";

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosSalarios;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosHonorarios;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosServicios;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosComisiones;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosPrestaciones;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosViaticos;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosRepresentacion;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosCompensaciones;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosOtros;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosCesantias;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pagosJubilacion;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $totalIngresosBrutos;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $aportesSalud;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $aportesPensionObligatorio;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $aportesPensionVoluntario;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $aportesAFC;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $valorRetencionFuente;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $pagadorNombre;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $pagadorNit;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $totalTexto;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $certificoTexto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDV(): ?int
    {
        return $this->DV;
    }

    public function setDV(int $DV): self
    {
        $this->DV = $DV;

        return $this;
    }

    public function getPrimerApellido(): ?string
    {
        return $this->primerApellido;
    }

    public function setPrimerApellido(string $primerApellido): self
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    public function getSegundoApellido(): ?string
    {
        return $this->segundoApellido;
    }

    public function setSegundoApellido(?string $segundoApellido): self
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getPeriodoCertificacionDe(): ?\DateTimeInterface
    {
        return $this->periodoCertificacionDe;
    }

    public function setPeriodoCertificacionDe(\DateTimeInterface $periodoCertificacionDe): self
    {
        $this->periodoCertificacionDe = $periodoCertificacionDe;

        return $this;
    }

    public function getPeriodoCertificacionA(): ?\DateTimeInterface
    {
        return $this->periodoCertificacionA;
    }

    public function setPeriodoCertificacionA(\DateTimeInterface $periodoCertificacionA): self
    {
        $this->periodoCertificacionA = $periodoCertificacionA;

        return $this;
    }

    public function getFechaExpedicion(): ?\DateTimeInterface
    {
        return $this->fechaExpedicion;
    }

    public function setFechaExpedicion(\DateTimeInterface $fechaExpedicion): self
    {
        $this->fechaExpedicion = $fechaExpedicion;

        return $this;
    }

    public function getLugarRetencion(): ?string
    {
        return $this->lugarRetencion;
    }

    public function setLugarRetencion(?string $lugarRetencion): self
    {
        $this->lugarRetencion = $lugarRetencion;

        return $this;
    }

    public function getCodigoDepartamento(): ?string
    {
        return $this->codigoDepartamento;
    }

    public function setCodigoDepartamento(string $codigoDepartamento): self
    {
        $this->codigoDepartamento = $codigoDepartamento;

        return $this;
    }

    public function getCodigoCiudad(): ?string
    {
        return $this->codigoCiudad;
    }

    public function setCodigoCiudad(string $codigoCiudad): self
    {
        $this->codigoCiudad = $codigoCiudad;

        return $this;
    }

    public function getPagosSalarios(): ?string
    {
        return $this->pagosSalarios;
    }

    public function setPagosSalarios(?string $pagosSalarios): self
    {
        $this->pagosSalarios = $pagosSalarios;

        return $this;
    }

    public function getPagosHonorarios(): ?string
    {
        return $this->pagosHonorarios;
    }

    public function setPagosHonorarios(?string $pagosHonorarios): self
    {
        $this->pagosHonorarios = $pagosHonorarios;

        return $this;
    }

    public function getPagosServicios(): ?string
    {
        return $this->pagosServicios;
    }

    public function setPagosServicios(?string $pagosServicios): self
    {
        $this->pagosServicios = $pagosServicios;

        return $this;
    }

    public function getPagosComisiones(): ?string
    {
        return $this->pagosComisiones;
    }

    public function setPagosComisiones(?string $pagosComisiones): self
    {
        $this->pagosComisiones = $pagosComisiones;

        return $this;
    }

    public function getPagosPrestaciones(): ?string
    {
        return $this->pagosPrestaciones;
    }

    public function setPagosPrestaciones(?string $pagosPrestaciones): self
    {
        $this->pagosPrestaciones = $pagosPrestaciones;

        return $this;
    }

    public function getPagosViaticos(): ?string
    {
        return $this->pagosViaticos;
    }

    public function setPagosViaticos(?string $pagosViaticos): self
    {
        $this->pagosViaticos = $pagosViaticos;

        return $this;
    }

    public function getPagosRepresentacion(): ?string
    {
        return $this->pagosRepresentacion;
    }

    public function setPagosRepresentacion(?string $pagosRepresentacion): self
    {
        $this->pagosRepresentacion = $pagosRepresentacion;

        return $this;
    }

    public function getPagosCompensaciones(): ?string
    {
        return $this->pagosCompensaciones;
    }

    public function setPagosCompensaciones(?string $pagosCompensaciones): self
    {
        $this->pagosCompensaciones = $pagosCompensaciones;

        return $this;
    }

    public function getPagosOtros(): ?string
    {
        return $this->pagosOtros;
    }

    public function setPagosOtros(?string $pagosOtros): self
    {
        $this->pagosOtros = $pagosOtros;

        return $this;
    }

    public function getPagosCesantias(): ?string
    {
        return $this->pagosCesantias;
    }

    public function setPagosCesantias(?string $pagosCesantias): self
    {
        $this->pagosCesantias = $pagosCesantias;

        return $this;
    }

    public function getPagosJubilacion(): ?string
    {
        return $this->pagosJubilacion;
    }

    public function setPagosJubilacion(?string $pagosJubilacion): self
    {
        $this->pagosJubilacion = $pagosJubilacion;

        return $this;
    }

    public function getTotalIngresosBrutos(): ?string
    {
        return $this->totalIngresosBrutos;
    }

    public function setTotalIngresosBrutos(?string $totalIngresosBrutos): self
    {
        $this->totalIngresosBrutos = $totalIngresosBrutos;

        return $this;
    }

    public function getAportesSalud(): ?string
    {
        return $this->aportesSalud;
    }

    public function setAportesSalud(?string $aportesSalud): self
    {
        $this->aportesSalud = $aportesSalud;

        return $this;
    }

    public function getAportesPensionObligatorio(): ?string
    {
        return $this->aportesPensionObligatorio;
    }

    public function setAportesPensionObligatorio(?string $aportesPensionObligatorio): self
    {
        $this->aportesPensionObligatorio = $aportesPensionObligatorio;

        return $this;
    }

    public function getAportesPensionVoluntario(): ?string
    {
        return $this->aportesPensionVoluntario;
    }

    public function setAportesPensionVoluntario(?string $aportesPensionVoluntario): self
    {
        $this->aportesPensionVoluntario = $aportesPensionVoluntario;

        return $this;
    }

    public function getAportesAFC(): ?string
    {
        return $this->aportesAFC;
    }

    public function setAportesAFC(?string $aportesAFC): self
    {
        $this->aportesAFC = $aportesAFC;

        return $this;
    }

    public function getValorRetencionFuente(): ?string
    {
        return $this->valorRetencionFuente;
    }

    public function setValorRetencionFuente(?string $valorRetencionFuente): self
    {
        $this->valorRetencionFuente = $valorRetencionFuente;

        return $this;
    }

    public function getPagadorNombre(): ?string
    {
        return $this->pagadorNombre;
    }

    public function setPagadorNombre(string $pagadorNombre): self
    {
        $this->pagadorNombre = $pagadorNombre;

        return $this;
    }

    public function getPagadorNit(): ?string
    {
        return $this->pagadorNit;
    }

    public function setPagadorNit(string $pagadorNit): self
    {
        $this->pagadorNit = $pagadorNit;

        return $this;
    }

    public function getTotalTexto(): ?string
    {
        return $this->totalTexto;
    }

    public function setTotalTexto(?string $totalTexto): self
    {
        $this->totalTexto = $totalTexto;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCertificoTexto(): ?string
    {
        return $this->certificoTexto;
    }

    public function setCertificoTexto(?string $certificoTexto): self
    {
        $this->certificoTexto = $certificoTexto;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }
}
