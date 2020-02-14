<?php

namespace App\Entity\Hv;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Hv\HvChild as HvChildConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\RedSocialRepository")
 * @HvChildConstraint(
 *     message="No puede tener mas de una red social del mismo tipo",
 *     uniqueFields={"tipo"}
 * )
 */
class RedSocial implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Ingrese tipo de red social")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $tipo;

    /**
     * @var int
     * @Groups("messenger:hv-child:put")
     */
    private $tipoPrev;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull(message="Ingrese cuenta")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "La cuenta supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $cuenta;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="redesSociales")
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

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(?int $tipo): self
    {
        $this->tipoPrev = $this->tipo;
        $this->tipo = $tipo;
        return $this;
    }

    public function getTipoPrev(): ?int
    {
        return $this->tipoPrev;
    }

    public function setTipoPrev(int $tipoPrev): RedSocial
    {
        $this->tipoPrev = $tipoPrev;
        return $this;
    }

    public function getCuenta(): ?string
    {
        return $this->cuenta;
    }

    public function setCuenta(?string $cuenta): self
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    public function getNapiId(): string
    {
        $tipo = $this->tipoPrev ?? $this->tipo;
        return "hv={$this->hv->getNapiId()};tipo=$tipo";
    }
}
