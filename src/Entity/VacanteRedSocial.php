<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacanteRedSocialRepository")
 */
class VacanteRedSocial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vacante", inversedBy="redesSociales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vacante;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idPost;

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

    public function getVacante(): ?Vacante
    {
        return $this->vacante;
    }

    public function setVacante(?Vacante $vacante): self
    {
        $this->vacante = $vacante;

        return $this;
    }

    public function getIdPost(): ?string
    {
        return $this->idPost;
    }

    public function setIdPost(?string $idPost): self
    {
        $this->idPost = $idPost;

        return $this;
    }
}
