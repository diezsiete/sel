<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\CompaniaRepository")
 */
class Compania
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=2)
     */
    private $compania;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $nit;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $representa;

    /**
     * @ORM\Column(type="boolean")
     */
    private $noupdate;

    public function getCompania(): ?string
    {
        return $this->compania;
    }

    public function setCompania(string $compania): self
    {
        $this->compania = $compania;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNit(): ?string
    {
        return $this->nit;
    }

    public function setNit(?string $nit): self
    {
        $this->nit = $nit;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getRepresenta(): ?string
    {
        return $this->representa;
    }

    public function setRepresenta(?string $representa): self
    {
        $this->representa = $representa;

        return $this;
    }

    public function getNoupdate(): ?bool
    {
        return $this->noupdate;
    }

    public function setNoupdate(bool $noupdate): self
    {
        $this->noupdate = $noupdate;

        return $this;
    }
}
