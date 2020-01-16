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

    public function getCompania(): ?string
    {
        return $this->compania;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getNit(): ?string
    {
        return $this->nit;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function getRepresenta(): ?string
    {
        return $this->representa;
    }
}
