<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScraperProcessRepository")
 */
class ScraperProcess
{
    const WAITING = 3;
    const EXECUTING = 2;
    const SUCCESS = 1;
    const FAILED = 0;

    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $scrapperId;


    /**
     * @ORM\Column(type="smallint")
     */
    private $estado;

    /**
     * @ORM\Column(type="smallint")
     */
    private $porcentaje;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;


    public function __construct($scrapperId, $estado, $porcentaje, Usuario $usuario = null)
    {
        $this->scrapperId = $scrapperId;
        $this->estado = $estado;
        $this->porcentaje = $porcentaje;
        if($usuario) {
            $this->usuario = $usuario;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPorcentaje(): ?int
    {
        return $this->porcentaje;
    }

    public function setPorcentaje(int $porcentaje): self
    {
        $this->porcentaje = $porcentaje;

        return $this;
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

    public function getScrapperId(): ?int
    {
        return $this->scrapperId;
    }

    public function setScrapperId(int $scrapperId): self
    {
        $this->scrapperId = $scrapperId;

        return $this;
    }
}
