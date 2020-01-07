<?php

namespace App\Entity\Hv;

use App\Entity\Vacante\Vacante;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\CargoRepository")
 */
class Cargo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vacante\Vacante", mappedBy="cargo")
     */
    private $vacantes;

    public function __construct()
    {
        $this->vacantes = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Cargo
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $name): self
    {
        $this->nombre = $name;

        return $this;
    }

    /**
     * @return Collection|\App\Entity\Vacante\Vacante[]
     */
    public function getVacantes(): Collection
    {
        return $this->vacantes;
    }

    public function addVacante(Vacante $vacante): self
    {
        if (!$this->vacantes->contains($vacante)) {
            $this->vacantes[] = $vacante;
            $vacante->addCargo($this);
        }

        return $this;
    }

    public function removeVacante(Vacante $vacante): self
    {
        if ($this->vacantes->contains($vacante)) {
            $this->vacantes->removeElement($vacante);
            $vacante->removeCargo($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
