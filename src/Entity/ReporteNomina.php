<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReporteNominaRepository")
 */
class ReporteNomina
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $convenioCodigoNombre;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $pension;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $salud;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $banco;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $salario;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $devengadosTotal;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $deducidosTotal;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $neto;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $netoTexto;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $baseSalario;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $baseRetencion;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $metRetencion;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $porcentajeRetencion;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $diasVacacionesPend;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReporteNominaDetalle", mappedBy="reporteNomina", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $detalles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $basePension;

    public function __construct()
    {
        $this->detalles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getConvenioCodigoNombre(): ?string
    {
        return $this->convenioCodigoNombre;
    }

    public function setConvenioCodigoNombre(string $convenioCodigoNombre): self
    {
        $this->convenioCodigoNombre = $convenioCodigoNombre;

        return $this;
    }

    public function getPension(): ?string
    {
        return $this->pension;
    }

    public function setPension(?string $pension): self
    {
        $this->pension = $pension;

        return $this;
    }

    public function getSalud(): ?string
    {
        return $this->salud;
    }

    public function setSalud(?string $salud): self
    {
        $this->salud = $salud;

        return $this;
    }

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function setBanco(?string $banco): self
    {
        $this->banco = $banco;

        return $this;
    }

    public function getCuenta(): ?string
    {
        return $this->cuenta;
    }

    public function setCuenta(?string $cuenta): self
    {
        $this->cuenta = $cuenta;

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

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(?string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getDevengadosTotal(): ?string
    {
        return $this->devengadosTotal;
    }

    public function setDevengadosTotal(?string $devengadosTotal): self
    {
        $this->devengadosTotal = $devengadosTotal;

        return $this;
    }

    public function getDeducidosTotal(): ?string
    {
        return $this->deducidosTotal;
    }

    public function setDeducidosTotal(?string $deducidosTotal): self
    {
        $this->deducidosTotal = $deducidosTotal;

        return $this;
    }

    public function getNeto(): ?string
    {
        return $this->neto;
    }

    public function setNeto(?string $neto): self
    {
        $this->neto = $neto;

        return $this;
    }

    public function getNetoTexto(): ?string
    {
        return $this->netoTexto;
    }

    public function setNetoTexto(?string $netoTexto): self
    {
        $this->netoTexto = $netoTexto;

        return $this;
    }

    public function getBaseSalario(): ?string
    {
        return $this->baseSalario;
    }

    public function setBaseSalario(?string $baseSalario): self
    {
        $this->baseSalario = $baseSalario;

        return $this;
    }

    public function getBaseRetencion(): ?string
    {
        return $this->baseRetencion;
    }

    public function setBaseRetencion(?string $baseRetencion): self
    {
        $this->baseRetencion = $baseRetencion;

        return $this;
    }

    public function getMetRetencion(): ?string
    {
        return $this->metRetencion;
    }

    public function setMetRetencion(?string $metRetencion): self
    {
        $this->metRetencion = $metRetencion;

        return $this;
    }

    public function getPorcentajeRetencion(): ?string
    {
        return $this->porcentajeRetencion;
    }

    public function setPorcentajeRetencion(?string $porcentajeRetencion): self
    {
        $this->porcentajeRetencion = $porcentajeRetencion;

        return $this;
    }

    public function getDiasVacacionesPend(): ?string
    {
        return $this->diasVacacionesPend;
    }

    public function setDiasVacacionesPend(?string $diasVacacionesPend): self
    {
        $this->diasVacacionesPend = $diasVacacionesPend;

        return $this;
    }

    /**
     * @return Collection|ReporteNominaDetalle[]
     */
    public function getDetalles(): Collection
    {
        return $this->detalles;
    }

    public function addDetalle(ReporteNominaDetalle $detalle): self
    {
        if (!$this->detalles->contains($detalle)) {
            $this->detalles[] = $detalle;
            $detalle->setReporteNomina($this);
        }

        return $this;
    }

    public function removeDetalle(ReporteNominaDetalle $detalle): self
    {
        if ($this->detalles->contains($detalle)) {
            $this->detalles->removeElement($detalle);
            // set the owning side to null (unless already changed)
            if ($detalle->getReporteNomina() === $this) {
                $detalle->setReporteNomina(null);
            }
        }

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

    public function getBasePension(): ?string
    {
        return $this->basePension;
    }

    public function setBasePension(?string $basePension): self
    {
        $this->basePension = $basePension;

        return $this;
    }
}
