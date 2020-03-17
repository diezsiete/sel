<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/identificacion-tipos"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/identificacion-tipo/{id}"},
 *     },
 *     normalizationContext={"groups"={"t3rs:read"}},
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity()
 */
class IdentificacionTipo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=2)
     * @Groups("t3rs:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     * @Groups("t3rs:read")
     */
    private $nombre;

    public function getId()
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
