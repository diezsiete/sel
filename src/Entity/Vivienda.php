<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ViviendaRepository")
 */
class Vivienda
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv", inversedBy="viviendas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hv;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto")
     */
    private $dpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ciudad")
     */
    private $ciudad;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $estrato;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $tipoVivienda;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $tenedor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $viviendaActual;

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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getDpto(): ?Dpto
    {
        return $this->dpto;
    }

    public function setDpto(?Dpto $dpto): self
    {
        $this->dpto = $dpto;

        return $this;
    }

    public function getCiudad(): ?Ciudad
    {
        return $this->ciudad;
    }

    public function setCiudad(?Ciudad $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getEstrato(): ?int
    {
        return $this->estrato;
    }

    public function setEstrato(?int $estrato): self
    {
        $this->estrato = $estrato;

        return $this;
    }

    public function getTipoVivienda(): ?int
    {
        return $this->tipoVivienda;
    }

    public function setTipoVivienda(?int $tipoVivienda): self
    {
        $this->tipoVivienda = $tipoVivienda;

        return $this;
    }

    public function getTenedor(): ?int
    {
        return $this->tenedor;
    }

    public function setTenedor(?int $tenedor): self
    {
        $this->tenedor = $tenedor;

        return $this;
    }

    public function getViviendaActual(): ?bool
    {
        return $this->viviendaActual;
    }

    public function setViviendaActual(?bool $viviendaActual): self
    {
        $this->viviendaActual = $viviendaActual;

        return $this;
    }
}
