<?php

namespace App\Entity\ServicioEmpleados;

use App\Entity\Main\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicioEmpleados\CertificadoIngresosRepository")
 * @ORM\Table(name="se_certificado_ingresos")
 */
class CertificadoIngresos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $periodo;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriodo(): ?\DateTimeInterface
    {
        return $this->periodo;
    }

    public function setPeriodo(\DateTimeInterface $periodo): self
    {
        $this->periodo = $periodo;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

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
}
