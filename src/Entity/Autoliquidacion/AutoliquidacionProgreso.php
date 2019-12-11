<?php

namespace App\Entity\Autoliquidacion;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Autoliquidacion\AutoliquidacionProgresoRepository")
 */
class AutoliquidacionProgreso
{

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
    private $porcentaje;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastMessage;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastMessage(): ?string
    {
        return $this->lastMessage;
    }

    public function setLastMessage(?string $lastMessage): self
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }
}
