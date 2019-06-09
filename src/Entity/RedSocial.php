<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RedSocialRepository")
 */
class RedSocial extends HvEntity
{
    /**
     * @ORM\Column(type="smallint")
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=145)
     */
    private $cuenta;

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getCuenta(): ?string
    {
        return $this->cuenta;
    }

    public function setCuenta(string $cuenta): self
    {
        $this->cuenta = $cuenta;

        return $this;
    }
}
