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
    private $porcentaje = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastMessage;

    /**
     * @ORM\Column(type="integer")
     */
    private $count = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $total = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPorcentaje(): ?int
    {
        return $this->porcentaje;
    }

    public function calcPorcentaje()
    {
        $porcentaje = $this->total ? $this->count * 100 / $this->total : 0;
        $porcentaje = floor($porcentaje);
        $this->porcentaje = intval($porcentaje);
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

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function addCount()
    {
        $this->count = $this->count + 1;
        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }


}
