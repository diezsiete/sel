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
     * @ORM\Column(type="smallint")
     * @Assert\NotNull(message="Ingrese tipo de red social")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=145)
     * @Assert\NotNull(message="Ingrese cuenta")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $cuenta;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="redesSociales")
     * @ORM\JoinColumn(nullable=false)
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
        $this->tipo = $tipo;

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
}
