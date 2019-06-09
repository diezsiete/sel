<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExperienciaRepository")
 */
class Experiencia extends HvEntity
{
    /**
     * @ORM\Column(type="string", length=55)
     * @Assert\NotNull(message="Ingrese nombre de la empresa")
     * @Groups("main")
     */
    private $empresa;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotNull(message="Ingrese cargo")
     * @Groups("main")
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Selecione area")
     * @Groups("main")
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="Ingrese descripcion")
     * @Groups("main")
     */
    private $descripcion;

    /**
     * @ORM\Column(type="smallint")
     * @Groups("main")
     */
    private $duracion;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("main")
     */
    private $logrosObtenidos;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("main")
     */
    private $motivoRetiro;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(message="Ingrese nombre del jefe")
     * @Groups("main")
     */
    private $jefeInmediato;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups("main")
     */
    private $salarioBasico;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotNull(message="Ingrese telefono del jefe inmediato")
     * @Groups("main")
     */
    private $telefonoJefe;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotNull(message="Ingrese fecha")
     * @Groups("main")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("main")
     */
    private $fechaRetiro;


    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function setEmpresa(string $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getLogrosObtenidos(): ?string
    {
        return $this->logrosObtenidos;
    }

    public function setLogrosObtenidos(?string $logrosObtenidos): self
    {
        $this->logrosObtenidos = $logrosObtenidos;

        return $this;
    }

    public function getMotivoRetiro(): ?string
    {
        return $this->motivoRetiro;
    }

    public function setMotivoRetiro(?string $motivoRetiro): self
    {
        $this->motivoRetiro = $motivoRetiro;

        return $this;
    }

    public function getJefeInmediato(): ?string
    {
        return $this->jefeInmediato;
    }

    public function setJefeInmediato(?string $jefeInmediato): self
    {
        $this->jefeInmediato = $jefeInmediato;

        return $this;
    }

    public function getSalarioBasico(): ?string
    {
        return $this->salarioBasico;
    }

    public function setSalarioBasico(?string $salarioBasico): self
    {
        $this->salarioBasico = $salarioBasico;

        return $this;
    }

    public function getTelefonoJefe(): ?string
    {
        return $this->telefonoJefe;
    }

    public function setTelefonoJefe(?string $telefonoJefe): self
    {
        $this->telefonoJefe = $telefonoJefe;

        return $this;
    }

    public function getFechaIngreso(): ?\DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    public function setFechaIngreso(?\DateTimeInterface $fechaIngreso): self
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
}
