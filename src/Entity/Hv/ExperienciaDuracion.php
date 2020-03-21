<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/experiencia-duraciones"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/experiencia-duracion/{id}"},
 *     },
 *     normalizationContext={"groups"={"api:experiencia-duracion:read"}},
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity()
 */
class ExperienciaDuracion
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "api:experiencia-duracion:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=21)
     * @Groups({"main", "api:experiencia-duracion:read"})
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