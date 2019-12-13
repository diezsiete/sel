<?php

namespace App\Entity\Novasoft\Report\LiquidacionNomina;

use App\Entity\Convenio;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenRepository")
 */
class LiquidacionNominaResumen
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio")
     * @ORM\JoinColumn(nullable=false)
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
     * @ORM\OneToMany(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenRenglon", mappedBy="liquidacionNominaResumen", orphanRemoval=true)
     */
    private $renglones;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenTotal", mappedBy="liquidacionNominaResumen", cascade={"persist", "remove"})
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina", mappedBy="resumen")
     */
    private $liquidacionesNomina;

    public function __construct()
    {
        $this->renglones = new ArrayCollection();
        $this->liquidacionesNomina = new ArrayCollection();
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

    /**
     * @return Collection|LiquidacionNominaResumenRenglon[]
     */
    public function getRenglones(): Collection
    {
        return $this->renglones;
    }

    public function addRenglon(LiquidacionNominaResumenRenglon $renglon): self
    {
        if (!$this->renglones->contains($renglon)) {
            $this->renglones[] = $renglon;
            $renglon->setLiquidacionNominaResumen($this);
        }

        return $this;
    }

    public function removeRenglon(LiquidacionNominaResumenRenglon $renglon): self
    {
        if ($this->renglones->contains($renglon)) {
            $this->renglones->removeElement($renglon);
            // set the owning side to null (unless already changed)
            if ($renglon->getLiquidacionNominaResumen() === $this) {
                $renglon->setLiquidacionNominaResumen(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?LiquidacionNominaResumenTotal
    {
        return $this->total;
    }

    public function setTotal(LiquidacionNominaResumenTotal $total): self
    {
        $this->total = $total;

        // set the owning side of the relation if necessary
        if ($this !== $total->getLiquidacionNominaResumen()) {
            $total->setLiquidacionNominaResumen($this);
        }

        return $this;
    }

    /**
     * @return Collection|LiquidacionNomina[]
     */
    public function getLiquidacionesNomina(): Collection
    {
        return $this->liquidacionesNomina;
    }

    public function addLiquidacionNomina(LiquidacionNomina $liquidacionesNomina): self
    {
        if (!$this->liquidacionesNomina->contains($liquidacionesNomina)) {
            $this->liquidacionesNomina[] = $liquidacionesNomina;
            $liquidacionesNomina->setResumen($this);
        }

        return $this;
    }

    public function removeLiquidacionNomina(LiquidacionNomina $liquidacionesNomina): self
    {
        if ($this->liquidacionesNomina->contains($liquidacionesNomina)) {
            $this->liquidacionesNomina->removeElement($liquidacionesNomina);
            // set the owning side to null (unless already changed)
            if ($liquidacionesNomina->getResumen() === $this) {
                $liquidacionesNomina->setResumen(null);
            }
        }

        return $this;
    }
}
