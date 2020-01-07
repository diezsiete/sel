<?php

namespace App\Entity\Vacante;

use App\Entity\Vacante\Vacante;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Vacante\VacanteAreaRepository")
 */
class VacanteArea
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vacante\Vacante", mappedBy="area")
     */
    private $vacantes;

    public function __construct()
    {
        $this->vacantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return VacanteArea
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

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Vacante[]
     */
    public function getVacantes(): Collection
    {
        return $this->vacantes;
    }

    public function addVacante(Vacante $vacante): self
    {
        if (!$this->vacantes->contains($vacante)) {
            $this->vacantes[] = $vacante;
            $vacante->addArea($this);
        }

        return $this;
    }

    public function removeVacante(Vacante $vacante): self
    {
        if ($this->vacantes->contains($vacante)) {
            $this->vacantes->removeElement($vacante);
            $vacante->removeArea($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
