<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\PeriodoRepository")
 */
class Periodo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=5)
     */
    private $consecLiq;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $liqDesde;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $liqHasta;

    /**
     * @ORM\Column(type="string", length=118, nullable=true)
     */
    private $mensaje;

    /**
     * @ORM\Column(type="boolean")
     */
    private $noupdate = false;

    public function getConsecLiq(): ?string
    {
        return $this->consecLiq;
    }

    public function setConsecLiq(string $consecLiq): self
    {
        $this->consecLiq = $consecLiq;

        return $this;
    }

    public function getLiqDesde(): ?\DateTimeInterface
    {
        return $this->liqDesde;
    }

    public function setLiqDesde(?\DateTimeInterface $liqDesde): self
    {
        $this->liqDesde = $liqDesde;

        return $this;
    }

    public function getLiqHasta(): ?\DateTimeInterface
    {
        return $this->liqHasta;
    }

    public function setLiqHasta(?\DateTimeInterface $liqHasta): self
    {
        $this->liqHasta = $liqHasta;

        return $this;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getNoupdate(): ?bool
    {
        return $this->noupdate;
    }

    public function setNoupdate(bool $noupdate): self
    {
        $this->noupdate = $noupdate;

        return $this;
    }
}
