<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfesionRepository")
 */
class Profesion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vacante", mappedBy="profesion")
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
            $vacante->addProfesion($this);
        }

        return $this;
    }

    public function removeVacante(Vacante $vacante): self
    {
        if ($this->vacantes->contains($vacante)) {
            $this->vacantes->removeElement($vacante);
            $vacante->removeProfesion($this);
        }

        return $this;
    }
}
