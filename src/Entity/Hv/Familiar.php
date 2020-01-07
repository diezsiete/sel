<?php

namespace App\Entity\Hv;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Hv\HvChild as HvChildConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\FamiliarRepository")
 * @HvChildConstraint(
 *     message="No puede tener familiares con mismo nombre",
 *     uniqueFields={"strtolower(primerApellido)", "strtolower(nombre)"}
 * )
 */
class Familiar implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="Ingrese primer apellido")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="Ingrese segundo apellido")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="Ingrese nombre")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $nombre;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotNull(message="Ingrese fecha de nacimiento")
     * @Assert\Date(message="Ingrese fecha valida")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotNull(message="Ingrese parentesco")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $parentesco;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull(message="Ingrese ocupación")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotNull(message="Ingrese genero")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $genero;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotNull(message="Ingrese estado civil")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $identificacion;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $identificacionTipo;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     * @Assert\NotNull(message="Ingrese nivel academico")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $nivelAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="familiares")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $hv;

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
