<?php

namespace App\Entity\Main;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Vinculacion
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Convenio|null
     * @ORM\ManyToOne(targetEntity="Convenio")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private $convenio;

    /**
     * @var Usuario|null
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private $usuario;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaRetiro;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Convenio|null
     */
    public function getConvenio(): ?Convenio
    {
        return $this->convenio;
    }

    /**
     * @param Convenio $convenio
     * @return Vinculacion
     */
    public function setConvenio(Convenio $convenio): Vinculacion
    {
        $this->convenio = $convenio;
        return $this;
    }

    /**
     * @return Usuario|null
     */
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     * @return Vinculacion
     */
    public function setUsuario(Usuario $usuario): Vinculacion
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getFechaIngreso(): ?DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    /**
     * @param DateTimeInterface $fechaIngreso
     * @return Vinculacion
     */
    public function setFechaIngreso(DateTimeInterface $fechaIngreso): Vinculacion
    {
        $this->fechaIngreso = $fechaIngreso;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getFechaRetiro(): ?DateTimeInterface
    {
        return $this->fechaRetiro;
    }

    /**
     * @param DateTimeInterface|null $fechaRetiro
     * @return Vinculacion
     */
    public function setFechaRetiro(?DateTimeInterface $fechaRetiro): Vinculacion
    {
        $this->fechaRetiro = $fechaRetiro;
        return $this;
    }

}