<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\RestaurarClaveRepository")
 */
class RestaurarClave
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="por favor ingrese identificación")
     */
    private $usuario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $restaurada = false;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $token;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getRestaurada(): ?bool
    {
        return $this->restaurada;
    }

    public function setRestaurada(bool $restaurada): self
    {
        $this->restaurada = $restaurada;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
