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
 *         "get" = {"path": "/estudio-codigos"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/estudio-codigo/{id}"},
 *     },
 *     normalizationContext={"groups"={"vue:read"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"nombre": "partial"})
 * @ORM\Entity(repositoryClass="App\Repository\Hv\EstudioCodigoRepository")
 */
class EstudioCodigo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=5)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "messenger:hv-child:put", "scraper", "scraper-hv-child", "selr:migrate"})
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
