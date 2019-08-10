<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstudioCodigoRepository")
 */
class EstudioCodigo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=7)
     * @Groups({"main", "scraper"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups("main")
     */
    private $nombre;

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return EstudioCodigo
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
