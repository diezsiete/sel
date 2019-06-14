<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdiomaRepository")
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
     * @Groups("main")
     */
    private $idiomaCodigo;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotNull(message="Ingrese nivel destreza")
     * @Groups("main")
     */
    private $destreza;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv", inversedBy="idiomas")
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

    public function getIdiomaCodigo(): ?string
    {
        return $this->idiomaCodigo;
    }

    public function setIdiomaCodigo(?string $idiomaCodigo): self
    {
        $this->idiomaCodigo = $idiomaCodigo;

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
}
