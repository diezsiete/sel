<?php

namespace App\Entity\Novasoft\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\CentroCostoRepository")
 * @ORM\Table(name="novasoft_centro_costo")
 */
class CentroCosto
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=20)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Novasoft\Report\TrabajadorActivo", mappedBy="centroCosto")
     */
    private $trabajadoresActivos;

    public function __construct()
    {
        $this->trabajadoresActivos = new ArrayCollection();
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|TrabajadorActivo[]
     */
    public function getTrabajadoresActivos(): Collection
    {
        return $this->trabajadoresActivos;
    }

    public function addTrabajadoresActivo(TrabajadorActivo $trabajadoresActivo): self
    {
        if (!$this->trabajadoresActivos->contains($trabajadoresActivo)) {
            $this->trabajadoresActivos[] = $trabajadoresActivo;
            $trabajadoresActivo->setCentroCosto($this);
        }

        return $this;
    }

    public function removeTrabajadoresActivo(TrabajadorActivo $trabajadoresActivo): self
    {
        if ($this->trabajadoresActivos->contains($trabajadoresActivo)) {
            $this->trabajadoresActivos->removeElement($trabajadoresActivo);
            // set the owning side to null (unless already changed)
            if ($trabajadoresActivo->getCentroCosto() === $this) {
                $trabajadoresActivo->setCentroCosto(null);
            }
        }

        return $this;
    }
}
