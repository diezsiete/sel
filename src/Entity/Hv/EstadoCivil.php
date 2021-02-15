<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/estados-civiles"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/estado-civil/{id}"},
 *     },
 *     normalizationContext={"groups"={"t3rs:read"}},
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity()
 */
class EstadoCivil
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     * @Groups({"napi:hv:post", "napi:hv:put", "t3rs:read", "selr:migrate"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=11)
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
