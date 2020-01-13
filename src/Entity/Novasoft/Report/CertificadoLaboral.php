<?php

namespace App\Entity\Novasoft\Report;

use App\Entity\Main\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\CertificadoLaboralRepository")
 * @ORM\Table(name="novasoft_certificado_laboral")
 */
class CertificadoLaboral
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $cedula;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $contrato;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $empresaUsuaria;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nsalario;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $salario;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hombre;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaEgreso;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $tipoDocumento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fechaIngresoTextual;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fechaCertificadoTextual;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNombreCompleto(): string
    {
        return $this->primerApellido . " " . ($this->segundoApellido ? "$this->segundoApellido " : "") . $this->nombre;
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

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(string $cedula): self
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getContrato(): ?string
    {
        return $this->contrato;
    }

    public function setContrato(string $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }

    public function getEmpresaUsuaria(): ?string
    {
        return $this->empresaUsuaria;
    }

    public function setEmpresaUsuaria(?string $empresaUsuaria): self
    {
        $this->empresaUsuaria = $empresaUsuaria;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(?string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getNsalario(): ?string
    {
        return $this->nsalario;
    }

    public function setNsalario(?string $nsalario): self
    {
        $this->nsalario = $nsalario;

        return $this;
    }

    public function getSalario(): ?string
    {
        return $this->salario;
    }

    public function setSalario(?string $salario): self
    {
        $this->salario = $salario;

        return $this;
    }

    public function isHombre(): ?bool
    {
        return $this->hombre;
    }

    public function setHombre(?bool $hombre): self
    {
        $this->hombre = $hombre;

        return $this;
    }

    public function getFechaIngreso(): ?\DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    public function setFechaIngreso(\DateTimeInterface $fechaIngreso): self
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    public function getFechaEgreso(): ?\DateTimeInterface
    {
        return $this->fechaEgreso;
    }

    public function setFechaEgreso(?\DateTimeInterface $fechaEgreso): self
    {
        $this->fechaEgreso = $fechaEgreso;

        return $this;
    }

    public function getTipoDocumento(): ?string
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(string $tipoDocumento): self
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFechaIngresoTextual(): ?string
    {
        return $this->fechaIngresoTextual;
    }

    public function setFechaIngresoTextual(?string $fechaIngresoTextual): self
    {
        $this->fechaIngresoTextual = $fechaIngresoTextual;

        return $this;
    }

    public function getFechaCertificadoTextual(): ?string
    {
        return $this->fechaCertificadoTextual;
    }

    public function setFechaCertificadoTextual(?string $fechaCertificadoTextual): self
    {
        $this->fechaCertificadoTextual = $fechaCertificadoTextual;

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
