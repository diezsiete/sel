<?php

namespace App\Entity\Hv;

use App\Validator\Hv\HvChild as HvChildConstraint;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * * @ApiResource(
 *     collectionOperations={"post"},
 *     itemOperations={"get", "put", "delete"},
 *     normalizationContext={"groups"={"api:cv:read"}},
 *     denormalizationContext={"groups"={"api:cv:write"}},
 * )
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
     * @Groups({"main", "api:cv:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="Ingrese primer apellido")
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "El apellido supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $primerApellido;

    /**
     * @var string
     * @Groups("messenger:hv-child:put")
     */
    private $primerApellidoPrev;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "El apellido supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="Ingrese nombre")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $nombre;

    /**
     * @var string
     * @Groups("messenger:hv-child:put")
     */
    private $nombrePrev;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotNull(message="Ingrese fecha de nacimiento")
     * @Assert\Date(message="Ingrese fecha valida")
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotNull(message="Ingrese parentesco")
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $parentesco;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Ingrese ocupación")
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Ingrese genero")
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $genero;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Ingrese estado civil")
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Assert\Length(
     *      max = 12,
     *      maxMessage = "La identificación supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $identificacion;

    /**
     * @ORM\Column(type="string", length=2)
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $identificacionTipo = 0;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotNull(message="Ingrese nivel academico")
     * @Groups({"main", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $nivelAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="familiares")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"napi:hv-child:post", "messenger:hv-child:put"})
     * @var Hv
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
        $this->primerApellidoPrev = $this->primerApellido;
        $this->primerApellido = $primerApellido;
        return $this;
    }

    public function getPrimerApellidoPrev(): ?string
    {
        return $this->primerApellidoPrev;
    }

    /**
     * @param string $primerApellidoPrev
     * @return Familiar
     */
    public function setPrimerApellidoPrev(string $primerApellidoPrev): Familiar
    {
        $this->primerApellidoPrev = $primerApellidoPrev;
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
        $this->nombrePrev = $this->nombre;
        $this->nombre = $nombre;
        return $this;
    }

    public function getNombrePrev(): ?string
    {
        return $this->nombrePrev;
    }

    /**
     * @param string $nombrePrev
     * @return Familiar
     */
    public function setNombrePrev(string $nombrePrev): Familiar
    {
        $this->nombrePrev = $nombrePrev;
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

    public function getNapiId(): string
    {
        $primerApellido = $this->primerApellidoPrev ?? $this->primerApellido;
        $nombre = $this->nombrePrev ?? $this->nombre;
        return "hv={$this->hv->getNapiId()};primerApellido=$primerApellido;nombre=$nombre";
    }
}
