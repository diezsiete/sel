<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Banco
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=4)
     */
    private $banco;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nombre;

    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }
}
