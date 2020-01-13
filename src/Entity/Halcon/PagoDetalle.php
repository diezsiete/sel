<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\PagoDetalleRepository")
 */
class PagoDetalle
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=8)
     */
    private $noContrat;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=5)
     */
    private $consecLiq;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=6)
     */
    private $concepto;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $novedad;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $valor;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $centroCos;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $oficina;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $cargo;

    public function getNoContrat(): ?string
    {
        return $this->noContrat;
    }

    public function setNoContrat(string $noContrat): self
    {
        $this->noContrat = $noContrat;

        return $this;
    }

    public function getConsecLiq(): ?string
    {
        return $this->consecLiq;
    }

    public function setConsecLiq(string $consecLiq): self
    {
        $this->consecLiq = $consecLiq;

        return $this;
    }

    public function getConcepto(): ?string
    {
        return $this->concepto;
    }

    public function setConcepto(string $concepto): self
    {
        $this->concepto = $concepto;

        return $this;
    }

    public function getDetalle(): ?string
    {
        return $this->detalle;
    }

    public function setDetalle(?string $detalle): self
    {
        $this->detalle = $detalle;

        return $this;
    }

    public function getNovedad(): ?int
    {
        return $this->novedad;
    }

    public function setNovedad(?int $novedad): self
    {
        $this->novedad = $novedad;

        return $this;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(?int $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getCentroCos(): ?string
    {
        return $this->centroCos;
    }

    public function setCentroCos(string $centroCos): self
    {
        $this->centroCos = $centroCos;

        return $this;
    }

    public function getOficina(): ?string
    {
        return $this->oficina;
    }

    public function setOficina(?string $oficina): self
    {
        $this->oficina = $oficina;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(?string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }
}
