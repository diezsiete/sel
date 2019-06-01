<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdiomaRepository")
 */
class Idioma
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=3)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
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
}
