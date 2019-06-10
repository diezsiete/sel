<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FamiliarRepository")
 */
class Familiar extends HvEntity
{
    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="Ingrese primer apellido")
     * @Groups("main")
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="Ingrese segundo apellido")
     * @Groups("main")
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="Ingrese nombre")
     * @Groups("main")
     */
    private $nombre;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotNull(message="Ingrese fecha de nacimiento")
     * @Assert\Date(message="Ingrese fecha valida")
     * @Groups("main")
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotNull(message="Ingrese parentesco")
     * @Groups("main")
     */
    private $parentesco;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull(message="Ingrese ocupación")
     * @Groups("main")
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotNull(message="Ingrese genero")
     * @Groups("main")
     */
    private $genero;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotNull(message="Ingrese estado civil")
     * @Groups("main")
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     * @Groups("main")
     */
    private $identificacion;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     * @Groups("main")
     */
    private $identificacionTipo;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     * @Assert\NotNull(message="Ingrese nivel academico")
     * @Groups("main")
     */
    private $nivelAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv", inversedBy="familiares")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $hv;

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

    public function setPrimerApellido(?string $primerApellido): self
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
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

    public function setParentesco(?string $parentesco): self
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    public function getOcupacion(): ?int
    {
        return $this->ocupacion;
    }

    public function setOcupacion(?int $ocupacion): self
    {
        $this->ocupacion = $ocupacion;

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

    public function getNivelAcademico(): ?string
    {
        return $this->nivelAcademico;
    }

    public function setNivelAcademico(?string $nivelAcademico): self
    {
        $this->nivelAcademico = $nivelAcademico;

        return $this;
    }
}
