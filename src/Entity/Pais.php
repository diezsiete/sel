<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaisRepository")
 */
class Pais
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dpto", mappedBy="pais", orphanRemoval=true)
     */
    private $dptos;


    public function __construct()
    {
        $this->dptos = new ArrayCollection();
    }

    public function getId(): ?string
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
     * @return Collection|Dpto[]
     */
    public function getDptos(): Collection
    {
        return $this->dptos;
    }

    public function addDpto(Dpto $dpto): self
    {
        if (!$this->dptos->contains($dpto)) {
            $this->dptos[] = $dpto;
            $dpto->setPais($this);
        }

        return $this;
    }

    public function removeDpto(Dpto $dpto): self
    {
        if ($this->dptos->contains($dpto)) {
            $this->dptos->removeElement($dpto);
            // set the owning side to null (unless already changed)
            if ($dpto->getPais() === $this) {
                $dpto->setPais(null);
            }
        }

        return $this;
    }
}
