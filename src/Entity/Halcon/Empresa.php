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
     * @ORM\ManyToOne(targetEntity="App\Entity\Halcon\Compania")
     * @ORM\JoinColumn(name="compania", referencedColumnName="compania")
     * @var Compania
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


    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function getCompania(): ?Compania
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

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function getResponsabl(): ?string
    {
        return $this->responsabl;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }
}
