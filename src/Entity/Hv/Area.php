<?php

namespace App\Entity\Hv;

use App\Entity\Vacante\Vacante;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\AreaRepository")
 */
class Area
{
    /**
     * TODO: en novasoft esto es smallint
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"main", "messenger:hv-child:put"})
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

    /**
     * @param mixed $id
     * @return Area
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getId()
    {
        return (int)$this->id;
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
        return $this->nombre;
    }
}
