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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dpto", mappedBy="pais")
     */
    private $dptos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ciudad", mappedBy="pais")
     */
    private $ciudades;

    public function __construct()
    {
        $this->dptos = new ArrayCollection();
        $this->ciudades = new ArrayCollection();
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

    /**
     * @return Collection|Ciudad[]
     */
    public function getCiudades(): Collection
    {
        return $this->ciudades;
    }

    public function addCiudade(Ciudad $ciudade): self
    {
        if (!$this->ciudades->contains($ciudade)) {
            $this->ciudades[] = $ciudade;
            $ciudade->setPais($this);
        }

        return $this;
    }

    public function removeCiudade(Ciudad $ciudade): self
    {
        if ($this->ciudades->contains($ciudade)) {
            $this->ciudades->removeElement($ciudade);
            // set the owning side to null (unless already changed)
            if ($ciudade->getPais() === $this) {
                $ciudade->setPais(null);
            }
        }

        return $this;
    }
}
