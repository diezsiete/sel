<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ViviendaRepository")
 */
class Vivienda extends HvEntity
{
    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotNull(message="Ingrese dirección")
     * @Groups("main")
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Seleccione pais donde se ubica la vivienda")
     * @Groups("main")
     */
    private $pais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto")
     * @Assert\NotNull(message="Seleccione departamento donde se ubica la vivienda")
     * @Groups("main")
     */
    private $dpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ciudad")
     * @Assert\NotNull(message="Seleccione ciudad donde se ubica la vivienda")
     * @Groups("main")
     */
    private $ciudad;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("main")
     */
    private $estrato;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotNull(message="Seleccione tipo de vivienda")
     * @Groups("main")
     */
    private $tipoVivienda;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotNull(message="Ingrese valor")
     * @Groups("main")
     */
    private $tenedor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("main")
     */
    private $viviendaActual;


    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
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
