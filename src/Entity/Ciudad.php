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
     * @ORM\ManyToOne(targetEntity="Pais")
     * @ORM\JoinColumn(name="pais_id", referencedColumnName="id")
     */
    protected $pais;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     * @ORM\Column(name="dpto_id", type="integer")
     */
    protected $dptoId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto", inversedBy="ciudades")
     * @ORM\JoinColumns(
     *     @ORM\JoinColumn(name="dpto_id", referencedColumnName="id"),
     *     @ORM\JoinColumn(name="pais_id", referencedColumnName="pais_id")
     * )
     */
    private $dpto;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;
    

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

    public function __toString()
    {
        return $this->getNombre();
    }
}
