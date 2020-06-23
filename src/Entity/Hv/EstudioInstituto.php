<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/estudio-institutos"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/estudio-instituto/{id}"},
 *     },
 *     normalizationContext={"groups"={"vue:read"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"nombre": "partial"})
 * @ORM\Entity(repositoryClass="App\Repository\Hv\EstudioInstitutoRepository")
 */
class EstudioInstituto
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=10)
     * @Groups({"vue:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=145)
     * @Groups({"vue:read"})
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
