<?php

namespace App\Entity\Hv;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Hv\HvChild as HvChildConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\ExperienciaRepository")
 * @HvChildConstraint(
 *     rules={
 *         {"uniqueFields": {"mb_strtoupper(empresa)", "area"}, "message"="No puede tener doble experiencia en la misma empresa y area"},
 *         {"uniqueFields": {"mb_strtoupper(cargo)", "mb_strtoupper(empresa)"}, "message"="No puede tener el mismo cargo en la misma empresa dos veces"}
 *     }
 * )
 */
class Experiencia implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotNull(message="Ingrese nombre de la empresa")
     * @Assert\Length(
     *      max = 150,
     *      maxMessage = "Nombre de la empresa supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $empresa;

    /**
     * @var string
     */
    private $empresaPrev;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotNull(message="Ingrese cargo")
     * @Assert\Length(
     *      max = 45,
     *      maxMessage = "El cargo supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $cargo;

    /**
     * @var string
     */
    private $cargoPrev;

    /**
     * @ORM\ManyToOne(targetEntity="Area", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Selecione area")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $area;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotNull(message="Ingrese descripcion")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $descripcion;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $logrosObtenidos;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull(message="Ingrese duracion")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $duracion;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $motivoRetiro;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(message="Ingrese nombre del jefe")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $jefeInmediato;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Salario basico supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $salarioBasico;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\NotNull(message="Ingrese telefono del jefe inmediato")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "El telefono supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $telefonoJefe;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotNull(message="Ingrese fecha")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $fechaRetiro;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="experiencia")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("napi:hv-child:post")
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


    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function setEmpresa(string $empresa): self
    {
        $this->empresaPrev = $this->empresa;
        $this->empresa = $empresa;
        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): self
    {
        $this->cargoPrev = $this->cargo;
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

    public function getNapiId(): string
    {
        $empresa = $this->empresaPrev ?? $this->empresa;
        $cargo = $this->cargoPrev ?? $this->cargo;
        return "hv={$this->hv->getNapiId()};empresa={$empresa};cargo={$cargo}";
    }
}
