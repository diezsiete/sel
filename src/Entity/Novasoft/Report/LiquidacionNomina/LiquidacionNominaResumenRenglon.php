<?php

namespace App\Entity\Novasoft\Report\LiquidacionNomina;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenRenglonRepository")
 */
class LiquidacionNominaResumenRenglon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen", inversedBy="renglones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $liquidacionNominaResumen;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $concepto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unidades;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $base;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $devengos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deducciones;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLiquidacionNominaResumen(): ?LiquidacionNominaResumen
    {
        return $this->liquidacionNominaResumen;
    }

    public function setLiquidacionNominaResumen(?LiquidacionNominaResumen $liquidacionNominaResumen): self
    {
        $this->liquidacionNominaResumen = $liquidacionNominaResumen;

        return $this;
    }

    public function getConcepto(): ?string
    {
        return $this->concepto;
    }

    public function setConcepto(?string $concepto): self
    {
        $this->concepto = $concepto;

        return $this;
    }

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(?int $unidades): self
    {
        $this->unidades = $unidades;

        return $this;
    }

    public function getBase(): ?int
    {
        return $this->base;
    }

    public function setBase(?int $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getDevengos(): ?int
    {
        return $this->devengos;
    }

    public function setDevengos(?int $devengos): self
    {
        $this->devengos = $devengos;

        return $this;
    }

    public function getDeducciones(): ?int
    {
        return $this->deducciones;
    }

    public function setDeducciones(?int $deducciones): self
    {
        $this->deducciones = $deducciones;

        return $this;
    }
}
