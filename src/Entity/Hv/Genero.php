<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/generos"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/genero/{id}"},
 *     },
 *     normalizationContext={"groups"={"t3rs:read"}},
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity()
 */
class Genero
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @Groups({"napi:hv:post", "napi:hv:put", "t3rs:read", "selr:migrate"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=9)
     * @Groups("t3rs:read")
     */
    private $nombre;

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

    public function __toString()
    {
        return $this->nombre;
    }
}
