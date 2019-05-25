<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmpleadoRepository")
 */
class Empleado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $identificacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $sexo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="smallint")
     */
    private $hijos;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $telefono1;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $telefono2;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=65, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=75)
     */
    private $centroCosto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(type="string", length=65, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio", inversedBy="empleados")
     * @ORM\JoinColumn(referencedColumnName="codigo")
     */
    private $convenio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificacion(): ?int
    {
        return $this->identificacion;
    }

    public function setIdentificacion(int $identificacion): self
    {
        $this->identificacion = $identificacion;

        return $this;
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

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getEstadoCivil(): ?string
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(string $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    public function getHijos(): ?int
    {
        return $this->hijos;
    }

    public function setHijos(int $hijos): self
    {
        $this->hijos = $hijos;

        return $this;
    }

    public function getNacimiento(): ?\DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(?\DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getTelefono1(): ?int
    {
        return $this->telefono1;
    }

    public function setTelefono1(?int $telefono1): self
    {
        $this->telefono1 = $telefono1;

        return $this;
    }

    public function getTelefono2(): ?int
    {
        return $this->telefono2;
    }

    public function setTelefono2(?int $telefono2): self
    {
        $this->telefono2 = $telefono2;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

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

    public function getCentroCosto(): ?string
    {
        return $this->centroCosto;
    }

    public function setCentroCosto(string $centroCosto): self
    {
        $this->centroCosto = $centroCosto;

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

    public function getFechaRetiro(): ?\DateTimeInterface
    {
        return $this->fechaRetiro;
    }

    public function setFechaRetiro(?\DateTimeInterface $fechaRetiro): self
    {
        $this->fechaRetiro = $fechaRetiro;

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

    public function getConvenio(): ?Convenio
    {
        return $this->convenio;
    }

    public function setConvenio(?Convenio $convenio): self
    {
        $this->convenio = $convenio;

        return $this;
    }
}
