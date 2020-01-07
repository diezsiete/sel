<?php

namespace App\Entity\Main;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\DptoRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Pais", inversedBy="dptos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pais;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Ciudad", mappedBy="dpto")
     */
    private $ciudades;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     * @Groups({"scraper", "scraper-hv", "scraper-hv-child"})
     * @SerializedName("id")
     */
    private $nId;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $nPaisId;

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

    public function getNId(): ?string
    {
        return $this->nId;
    }

    public function setNId(string $nId): self
    {
        $this->nId = $nId;

        return $this;
    }

    public function getNPaisId(): ?string
    {
        return $this->nPaisId;
    }

    public function setNPaisId(string $nPaisId): self
    {
        $this->nPaisId = $nPaisId;

        return $this;
    }
}
