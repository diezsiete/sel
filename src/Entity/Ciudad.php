<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CiudadRepository")
 */
class Ciudad
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     */
    private $id;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais", inversedBy="ciudades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pais;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto", inversedBy="ciudades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dpto;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vacante", mappedBy="ciudad")
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDpto(): ?Dpto
    {
        return $this->dpto;
    }

    public function setDpto(?Dpto $dpto): self
    {
        $this->dpto = $dpto;

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
            $vacante->addCiudad($this);
        }

        return $this;
    }

    public function removeVacante(Vacante $vacante): self
    {
        if ($this->vacantes->contains($vacante)) {
            $this->vacantes->removeElement($vacante);
            $vacante->removeCiudad($this);
        }

        return $this;
    }
}
