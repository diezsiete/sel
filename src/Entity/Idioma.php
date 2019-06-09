<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdiomaRepository")
 */
class Idioma extends HvEntity
{
    /**
     * @ORM\Column(type="string", length=3)
     */
    private $idiomaCodigo;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $destreza;


    public function getIdiomaCodigo(): ?string
    {
        return $this->idiomaCodigo;
    }

    public function setIdiomaCodigo(string $idiomaCodigo): self
    {
        $this->idiomaCodigo = $idiomaCodigo;

        return $this;
    }

    public function getDestreza(): ?string
    {
        return $this->destreza;
    }

    public function setDestreza(string $destreza): self
    {
        $this->destreza = $destreza;

        return $this;
    }
}
