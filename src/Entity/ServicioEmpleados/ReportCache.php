<?php

namespace App\Entity\ServicioEmpleados;

use App\Entity\Main\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicioEmpleados\ReportCacheRepository")
 */
class ReportCache
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $source;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastUpdate;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $report;

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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(string $report): self
    {
        $this->report = $report;

        return $this;
    }
}
