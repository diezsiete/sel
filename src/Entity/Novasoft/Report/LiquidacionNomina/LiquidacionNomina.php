<?php

namespace App\Entity\Novasoft\Report\LiquidacionNomina;

use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaRepository")
 */
class LiquidacionNomina
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Convenio", inversedBy="liquidacionesNominas")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="codigo")
     */
    private $convenio;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaInicial;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaFinal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Empleado", inversedBy="liquidacionesNomina", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empleado;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ingresoBasico;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $cargoCodigo;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaTotal",
     *     mappedBy="liquidacionNomina",
     *     cascade={"persist", "remove"}
     * )
     */
    private $total;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaRenglon",
     *     mappedBy="liquidacionNomina",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"},
     *     fetch="EAGER"
     * )
     */
    private $renglones;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen",
     *     inversedBy="liquidacionesNomina"
     * )
     */
    private $resumen;

    public function __construct()
    {
        $this->renglones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFechaInicial(): ?\DateTimeInterface
    {
        return $this->fechaInicial;
    }

    public function setFechaInicial(\DateTimeInterface $fechaInicial): self
    {
        $this->fechaInicial = $fechaInicial;

        return $this;
    }

    public function getFechaFinal(): ?\DateTimeInterface
    {
        return $this->fechaFinal;
    }

    public function setFechaFinal(\DateTimeInterface $fechaFinal): self
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    public function getEmpleado(): ?Empleado
    {
        return $this->empleado;
    }

    public function setEmpleado(?Empleado $empleado): self
    {
        $this->empleado = $empleado;

        return $this;
    }

    public function getIngresoBasico(): ?int
    {
        return $this->ingresoBasico;
    }

    public function setIngresoBasico(?int $ingresoBasico): self
    {
        $this->ingresoBasico = $ingresoBasico;

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

    public function getCargoCodigo(): ?string
    {
        return $this->cargoCodigo;
    }

    public function setCargoCodigo(?string $cargoCodigo): self
    {
        $this->cargoCodigo = $cargoCodigo;

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

    public function getCuenta(): ?string
    {
        return $this->cuenta;
    }

    public function setCuenta(?string $cuenta): self
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    public function getTotal(): ?LiquidacionNominaTotal
    {
        return $this->total;
    }

    public function setTotal(LiquidacionNominaTotal $total): self
    {
        $this->total = $total;

        // set the owning side of the relation if necessary
        if ($this !== $total->getLiquidacionNomina()) {
            $total->setLiquidacionNomina($this);
        }

        return $this;
    }

    /**
     * @return Collection|LiquidacionNominaRenglon[]
     */
    public function getRenglones(): Collection
    {
        return $this->renglones;
    }

    public function addRenglon(LiquidacionNominaRenglon $renglon): self
    {
        if (!$this->renglones->contains($renglon)) {
            $this->renglones[] = $renglon;
            $renglon->setLiquidacionNomina($this);
        }

        return $this;
    }

    public function removeRenglon(LiquidacionNominaRenglon $renglon): self
    {
        if ($this->renglones->contains($renglon)) {
            $this->renglones->removeElement($renglon);
            // set the owning side to null (unless already changed)
            if ($renglon->getLiquidacionNomina() === $this) {
                $renglon->setLiquidacionNomina(null);
            }
        }

        return $this;
    }

    public function getResumen(): ?LiquidacionNominaResumen
    {
        return $this->resumen;
    }

    public function setResumen(?LiquidacionNominaResumen $resumen): self
    {
        $this->resumen = $resumen;

        return $this;
    }
}
