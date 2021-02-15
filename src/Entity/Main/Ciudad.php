<?php

namespace App\Entity\Main;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/ciudades"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/ciudad/{id}"},
 *     },
 *     normalizationContext={"groups"={"main"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Main\CiudadRepository")
 * @ApiFilter(SearchFilter::class, properties={"pais": "exact", "dpto": "exact", "nombre": "partial"})
 */
class Ciudad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"main", "messenger:hv-child:put", "t3rs:ciudad:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main", "messenger:hv-child:put", "t3rs:ciudad:read"})
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Dpto", inversedBy="ciudades")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"main"})
     */
    private $dpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Pais", inversedBy="ciudades")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"main"})
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Groups({"napi:hv:post", "napi:hv:put", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv", "scraper-hv-child", "selr:migrate"})
     * @SerializedName("id")
     */
    private $nId;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $nPaisId;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $nDptoId;

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

    public function getNDptoId(): ?string
    {
        return $this->nDptoId;
    }

    public function setNDptoId(string $nDptoId): self
    {
        $this->nDptoId = $nDptoId;

        return $this;
    }
}
