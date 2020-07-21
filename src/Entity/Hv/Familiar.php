<?php

namespace App\Entity\Hv;

use App\Validator\Hv\HvChild as HvChildConstraint;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * * @ApiResource(
 *     collectionOperations={
 *         "post"={"path"="/familiar"}
 *     },
 *     itemOperations={
 *         "get"={"path"="/familiar"},
 *         "put"={"path"="/familiar"},
 *         "delete"={"path"="/familiar"}
 *     },
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
     * @Groups({"api:cv:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="Ingrese primer apellido")
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "El apellido supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
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
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="Ingrese nombre")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $nombre;

    /**
     * @var string
     * @Groups("messenger:hv-child:put")
     */
    private $nombrePrev;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="Ingrese fecha de nacimiento")
     * @Assert\Date(message="Ingrese fecha valida")
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $nacimiento;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\Parentesco")
     * @ORM\JoinColumn(name="parentesco", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Ingrese parentesco")
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $parentesco;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\Ocupacion")
     * @ORM\JoinColumn(name="ocupacion", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Ingrese ocupación")
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $ocupacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\Genero")
     * @ORM\JoinColumn(name="genero", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Ingrese genero")
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $genero;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\EstadoCivil")
     * @ORM\JoinColumn(name="estado_civil", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Ingrese estado civil")
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Assert\Length(
     *      max = 12,
     *      maxMessage = "La identificación supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $identificacion;

    /**
     * @ORM\ManyToOne(targetEntity="IdentificacionTipo")
     * @ORM\JoinColumn(name="identificacion_tipo", referencedColumnName="id", nullable=true)
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
     */
    private $identificacionTipo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\NivelAcademico")
     * @ORM\JoinColumn(name="nivel_academico", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Ingrese nivel academico")
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put"})
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

    public function getParentesco()
    {
        return $this->parentesco;
    }

    public function setParentesco($parentesco): self
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    public function getOcupacion()
    {
        return $this->ocupacion;
    }

    public function setOcupacion($ocupacion): self
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function setGenero($genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil($estadoCivil): self
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

    public function getIdentificacionTipo()
    {
        return $this->identificacionTipo;
    }

    public function setIdentificacionTipo($identificacionTipo): self
    {
        $this->identificacionTipo = $identificacionTipo;

        return $this;
    }

    public function getNivelAcademico()
    {
        return $this->nivelAcademico;
    }

    public function setNivelAcademico($nivelAcademico): self
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
