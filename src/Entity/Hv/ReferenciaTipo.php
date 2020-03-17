<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/referencia-tipos"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/referencia-tipo/{id}"},
 *     },
 *     normalizationContext={"groups"={"api:referencia-tipo:read"}},
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity()
 */
class ReferenciaTipo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     * @Groups("api:referencia-tipo:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8)
     * @Groups("api:referencia-tipo:read")
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
