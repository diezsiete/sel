<?php

namespace App\Entity\Novasoft\Report\LiquidacionNomina;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenTotalRepository")
 */
class LiquidacionNominaResumenTotal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unidades;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $devengos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deducciones;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $neto;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen", inversedBy="total", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $liquidacionNominaResumen;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNeto(): ?int
    {
        return $this->neto;
    }

    public function setNeto(?int $neto): self
    {
        $this->neto = $neto;

        return $this;
    }

    public function getLiquidacionNominaResumen(): ?LiquidacionNominaResumen
    {
        return $this->liquidacionNominaResumen;
    }

    public function setLiquidacionNominaResumen(LiquidacionNominaResumen $liquidacionNominaResumen): self
    {
        $this->liquidacionNominaResumen = $liquidacionNominaResumen;

        return $this;
    }
}
