<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=55)
     * @Assert\NotNull(message="Ingrese nombre de la empresa")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $empresa;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotNull(message="Ingrese cargo")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Selecione area")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="Ingrese descripcion")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $descripcion;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $duracion;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $logrosObtenidos;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $motivoRetiro;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(message="Ingrese nombre del jefe")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $jefeInmediato;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $salarioBasico;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotNull(message="Ingrese telefono del jefe inmediato")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $telefonoJefe;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotNull(message="Ingrese fecha")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $fechaRetiro;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv", inversedBy="experiencia")
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
