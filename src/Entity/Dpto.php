<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DptoRepository")
 */
class Dpto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais", inversedBy="dptos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pais;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ciudad", mappedBy="dpto")
     */
    private $ciudades;

    public function __construct()
    {
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

    public function addCiudad(Ciudad $ciudade): self
    {
        if (!$this->ciudades->contains($ciudade)) {
            $this->ciudades[] = $ciudade;
            $ciudade->setDpto($this);
        }

        return $this;
    }

    public function removeCiudad(Ciudad $ciudade): self
    {
        if ($this->ciudades->contains($ciudade)) {
            $this->ciudades->removeElement($ciudade);
            // set the owning side to null (unless already changed)
            if ($ciudade->getDpto() === $this) {
                $ciudade->setDpto(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
