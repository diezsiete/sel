<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FamiliarRepository")
 */
class Familiar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv", inversedBy="familiares")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hv;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nombre;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $parentesco;

    /**
     * @ORM\Column(type="smallint")
     */
    private $ocupacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NivelAcademico")
     */
    private $nivelAcademico;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $genero;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $identificacion;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $identificacionTipo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHv(): ?Hv
    {
        return $this->hv;
    }

    public function setHv(?Hv $hv): self
    {
        $this->hv = $hv;

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

    public function setSegundoApellido(string $segundoApellido): self
    {
        $this->segundoApellido = $segundoApellido;

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

    public function getNacimiento(): ?\DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(?\DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getParentesco(): ?string
    {
        return $this->parentesco;
    }

    public function setParentesco(string $parentesco): self
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    public function getOcupacion(): ?int
    {
        return $this->ocupacion;
    }

    public function setOcupacion(int $ocupacion): self
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getNivelAcademico(): ?NivelAcademico
    {
        return $this->nivelAcademico;
    }

    public function setNivelAcademico(?NivelAcademico $nivelAcademico): self
    {
        $this->nivelAcademico = $nivelAcademico;

        return $this;
    }

    public function getGenero(): ?int
    {
        return $this->genero;
    }

    public function setGenero(?int $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getEstadoCivil(): ?int
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(?int $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    public function getIdentificacion(): ?string
    {
        return $this->identificacion;
    }

    public function setIdentificacion(?string $identificacion): self
    {
        $this->identificacion = $identificacion;

        return $this;
    }

    public function getIdentificacionTipo(): ?string
    {
        return $this->identificacionTipo;
    }

    public function setIdentificacionTipo(?string $identificacionTipo): self
    {
        $this->identificacionTipo = $identificacionTipo;

        return $this;
    }
}
