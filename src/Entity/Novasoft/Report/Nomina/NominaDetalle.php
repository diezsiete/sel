<?php

namespace App\Entity\Novasoft\Report\Nomina;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\Nomina\NominaDetalleRepository")
 * @ORM\Table(name="novasoft_nomina_detalle")
 */
class NominaDetalle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $cantidad;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $valor;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity="Nomina", inversedBy="detalles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nomina;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getDetalle(): ?string
    {
        return $this->detalle;
    }

    public function setDetalle(?string $detalle): self
    {
        $this->detalle = $detalle;

        return $this;
    }

    public function getCantidad(): ?string
    {
        return $this->cantidad;
    }

    public function setCantidad(?string $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(?string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getNomina(): ?Nomina
    {
        return $this->nomina;
    }

    public function setNomina(?Nomina $nomina): self
    {
        $this->nomina = $nomina;

        return $this;
    }
}
