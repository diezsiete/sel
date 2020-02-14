<?php

namespace App\Entity\Hv;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Hv\HvChild as HvChildConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\IdiomaRepository")
 * @HvChildConstraint(
 *     message="No puede tener idiomas repetidos",
 *     uniqueFields={"idiomaCodigo"}
 * )
 */
class Idioma implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=3)
     * @Assert\NotNull(message="Ingrese idioma")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $idiomaCodigo;

    /**
     * @var string
     * @Groups("messenger:hv-child:put")
     */
    private $idiomaCodigoPrev;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotNull(message="Ingrese nivel destreza")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $destreza;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\Hv", inversedBy="idiomas")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"napi:hv-child:post", "messenger:hv-child:put"})
     * @var Hv
     */
    protected $hv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHv(): ?Hv
    {
        return $this->hv;
    }

    public function setHv(?Hv $hv): self
    {
        $this->hv = $hv;

        return $this;
    }

    public function getIdiomaCodigo(): ?string
    {
        return $this->idiomaCodigo;
    }

    public function setIdiomaCodigo(?string $idiomaCodigo): self
    {
        $this->idiomaCodigoPrev = $this->idiomaCodigo;
        $this->idiomaCodigo = $idiomaCodigo;
        return $this;
    }

    public function getIdiomaCodigoPrev(): ?string
    {
        return $this->idiomaCodigoPrev;
    }

    public function setIdiomaCodigoPrev(string $idiomaCodigoPrev): Idioma
    {
        $this->idiomaCodigoPrev = $idiomaCodigoPrev;
        return $this;
    }

    public function getDestreza(): ?string
    {
        return $this->destreza;
    }

    public function setDestreza(?string $destreza): self
    {
        $this->destreza = $destreza;

        return $this;
    }

    public function getNapiId(): string
    {
        $idiomaCodigo = $this->idiomaCodigoPrev ?? $this->idiomaCodigo;
        return "hv={$this->hv->getNapiId()};idiomaCodigo=$idiomaCodigo";
    }
}
