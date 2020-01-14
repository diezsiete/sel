<?php

namespace App\Entity\ServicioEmpleados;

use App\Entity\Main\Usuario;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicioEmpleados\NominaRepository")
 * @ORM\Table(name="se_nomina")
 */
class Nomina implements ReportInterface
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
    private $fecha;

    /**
     * @ORM\Column(type="string", length=105)
     */
    private $convenio;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $source = 'novasoft';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=27)
     */
    private $sourceId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getConvenio(): ?string
    {
        return $this->convenio;
    }

    public function setConvenio(string $convenio): self
    {
        $this->convenio = $convenio;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function isSourceNovasoft(): bool
    {
        return $this->source === 'novasoft';
    }

    public function isSourceHalcon(): bool
    {
        return $this->source === 'halcon';
    }

    public function setSourceNovasoft(): self
    {
        $this->source = 'novasoft';
        return $this;
    }

    public function setSourceHalcon(): self
    {
        $this->source = 'halcon';
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

    public function getSourceId(): ?string
    {
        return $this->sourceId;
    }

    public function setSourceId(string $sourceId): self
    {
        $this->sourceId = $sourceId;

        return $this;
    }
}
