<?php

namespace App\Entity\Novasoft\Report\LiquidacionNomina;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaTotalRepository")
 */
class LiquidacionNominaTotal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina", inversedBy="total", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $liquidacionNomina;

    /**
     * @ORM\Column(type="integer")
     */
    private $unidades;

    /**
     * @ORM\Column(type="integer")
     */
    private $devengos;

    /**
     * @ORM\Column(type="integer")
     */
    private $deducciones;

    /**
     * @ORM\Column(type="integer")
     */
    private $neto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLiquidacionNomina(): ?LiquidacionNomina
    {
        return $this->liquidacionNomina;
    }

    public function setLiquidacionNomina(LiquidacionNomina $liquidacionNomina): self
    {
        $this->liquidacionNomina = $liquidacionNomina;

        return $this;
    }

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(int $unidades): self
    {
        $this->unidades = $unidades;

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

    public function getNeto(): ?int
    {
        return $this->neto;
    }

    public function setNeto(int $neto): self
    {
        $this->neto = $neto;

        return $this;
    }
}
