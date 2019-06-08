<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstudioInstitutoRepository")
 */
class EstudioInstituto
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     * @Groups("main")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("main")
     */
    private $nombre;

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return EstudioInstituto
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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

    public function __toString()
    {
        return $this->nombre;
    }
}
