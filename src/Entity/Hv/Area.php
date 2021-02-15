<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Vacante\Vacante;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/areas"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/area/{id}"},
 *     },
 *     normalizationContext={"groups"={"api:cv:read", "messenger:hv-child:put"}},
 * )
 * @ApiFilter(SearchFilter::class, properties={"nombre": "partial"})
 * @ORM\Entity(repositoryClass="App\Repository\Hv\AreaRepository")
 */
class Area
{
    /**
     * TODO: en novasoft esto es smallint
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     * @Groups({"main", "api:cv:read", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "selr:migrate"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"main", "api:cv:read"})
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
