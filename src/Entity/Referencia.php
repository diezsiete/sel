<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReferenciaRepository")
 */
class Referencia extends HvEntity
{
    /**
     * @ORM\Column(type="smallint")
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=105)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=145)
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $parentesco;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $celular;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $entidad;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $direccion;


    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    public function setOcupacion(string $ocupacion): self
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getParentesco(): ?string
    {
        return $this->parentesco;
    }

    public function setParentesco(?string $parentesco): self
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(string $celular): self
    {
        $this->celular = $celular;

        return $this;
    }

    public function getEntidad(): ?string
    {
        return $this->entidad;
    }

    public function setEntidad(?string $entidad): self
    {
        $this->entidad = $entidad;

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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }
}
