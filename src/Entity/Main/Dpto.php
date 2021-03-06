<?php

namespace App\Entity\Main;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     shortName="Departamento",
 *     collectionOperations={
 *         "get" = {"path": "/departamentos"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/departamento/{id}"},
 *     },
 *     normalizationContext={"groups"={"t3rs:dpto:read"}},
 * )
 * @ApiFilter(SearchFilter::class, properties={"pais": "exact", "nombre": "partial"})
 * @ORM\Entity(repositoryClass="App\Repository\Main\DptoRepository")
 */
class Dpto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"main", "messenger:hv-child:put", "t3rs:dpto:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main", "messenger:hv-child:put", "t3rs:dpto:read"})
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Pais", inversedBy="dptos")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("t3rs:dpto:read")
     */
    private $pais;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Ciudad", mappedBy="dpto")
     */
    private $ciudades;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     * @Groups({"napi:hv:post", "napi:hv:put", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv", "scraper-hv-child", "selr:migrate"})
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
