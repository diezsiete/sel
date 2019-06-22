<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConvenioRepository")
 */
class Convenio
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=45)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $codigoCliente;

    /**
     * @ORM\Column(type="string", length=105)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=145)
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Empleado", mappedBy="convenio")
     */
    private $empleados;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ssrsDb;

    public function __construct()
    {
        $this->empleados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getCodigoCliente(): ?string
    {
        return $this->codigoCliente;
    }

    public function setCodigoCliente(string $codigoCliente): self
    {
        $this->codigoCliente = $codigoCliente;

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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * @return Collection|Empleado[]
     */
    public function getEmpleados(): Collection
    {
        return $this->empleados;
    }

    public function addEmpleado(Empleado $empleado): self
    {
        if (!$this->empleados->contains($empleado)) {
            $this->empleados[] = $empleado;
            $empleado->setConvenio($this);
        }

        return $this;
    }

    public function removeEmpleado(Empleado $empleado): self
    {
        if ($this->empleados->contains($empleado)) {
            $this->empleados->removeElement($empleado);
            // set the owning side to null (unless already changed)
            if ($empleado->getConvenio() === $this) {
                $empleado->setConvenio(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSsrsDb()
    {
        return $this->ssrsDb;
    }

    /**
     * @param mixed $ssrsDb
     * @return Convenio
     */
    public function setSsrsDb($ssrsDb)
    {
        $this->ssrsDb = $ssrsDb;
        return $this;
    }
}
