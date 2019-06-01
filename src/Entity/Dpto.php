<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DptoRepository")
 */
class Dpto
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     */
    private $id;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais", inversedBy="dptos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ciudad", mappedBy="dpto")
     */
    private $ciudades;

    public function __construct()
    {
        $this->ciudades = new ArrayCollection();
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

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * @return Collection|Ciudad[]
     */
    public function getCiudades(): Collection
    {
        return $this->ciudades;
    }

    public function addCiudad(Ciudad $ciudad): self
    {
        if (!$this->ciudades->contains($ciudad)) {
            $this->ciudades[] = $ciudad;
            $ciudad->setDpto($this);
        }

        return $this;
    }

    public function removeCiudad(Ciudad $ciudad): self
    {
        if ($this->ciudades->contains($ciudad)) {
            $this->ciudades->removeElement($ciudad);
            // set the owning side to null (unless already changed)
            if ($ciudad->getDpto() === $this) {
                $ciudad->setDpto(null);
            }
        }

        return $this;
    }
}
