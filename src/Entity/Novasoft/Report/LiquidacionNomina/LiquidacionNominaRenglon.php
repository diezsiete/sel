<?php

namespace App\Entity\Novasoft\Report\LiquidacionNomina;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaRenglonRepository")
 */
class LiquidacionNominaRenglon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $conceptoCodigo;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $concepto;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $unidades;

    /**
     * @ORM\Column(type="float")
     */
    private $base;

    /**
     * @ORM\Column(type="integer")
     */
    private $devengos;

    /**
     * @ORM\Column(type="integer")
     */
    private $deducciones;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $centroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina", inversedBy="renglones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $liquidacionNomina;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConceptoCodigo(): ?string
    {
        return $this->conceptoCodigo;
    }

    public function setConceptoCodigo(?string $conceptoCodigo): self
    {
        $this->conceptoCodigo = $conceptoCodigo;

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

    public function getUnidades(): ?float
    {
        return $this->unidades;
    }

    public function setUnidades(?float $unidades): self
    {
        $this->unidades = $unidades;

        return $this;
    }

    public function getBase(): ?float
    {
        return $this->base;
    }

    public function setBase(float $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getDevengos(): ?int
    {
        return $this->devengos;
    }

    public function setDevengos(int $devengos): self
    {
        $this->devengos = $devengos;

        return $this;
    }

    public function getDeducciones(): ?int
    {
        return $this->deducciones;
    }

    public function setDeducciones(int $deducciones): self
    {
        $this->deducciones = $deducciones;

        return $this;
    }

    public function getCentroCosto(): ?string
    {
        return $this->centroCosto;
    }

    public function setCentroCosto(?string $centroCosto): self
    {
        $this->centroCosto = $centroCosto;

        return $this;
    }

    public function getLiquidacionNomina(): ?LiquidacionNomina
    {
        return $this->liquidacionNomina;
    }

    public function setLiquidacionNomina(?LiquidacionNomina $liquidacionNomina): self
    {
        $this->liquidacionNomina = $liquidacionNomina;

        return $this;
    }
}
