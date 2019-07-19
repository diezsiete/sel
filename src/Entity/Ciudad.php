<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CiudadRepository")
 */
class Ciudad
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto", inversedBy="ciudades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais", inversedBy="ciudades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     * @Groups({"scrapper"})
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
