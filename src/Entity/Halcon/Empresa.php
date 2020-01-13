<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\EmpresaRepository")
 */
class Empresa
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=2)
     */
    private $usuario;

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
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ciudad;

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
    private $responsabl;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $estado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $noupdate = false;


    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

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

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(?string $ciudad): self
    {
        $this->ciudad = $ciudad;

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

    public function getResponsabl(): ?string
    {
        return $this->responsabl;
    }

    public function setResponsabl(?string $responsabl): self
    {
        $this->responsabl = $responsabl;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

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
