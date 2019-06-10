<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RedSocialRepository")
 */
class RedSocial extends HvEntity
{
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull(message="Ingrese tipo de red social")
     * @Groups("main")
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=145)
     * @Assert\NotNull(message="Ingrese cuenta")
     * @Groups("main")
     */
    private $cuenta;

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
