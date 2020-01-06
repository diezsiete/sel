<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\VinculacionRepository")
 */
class Vinculacion
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=8)
     */
    private $numeroContrato;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $nitTercero;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ingreso;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $retiro;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $centroCosto;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $oficina;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $sueldoMes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $auxilioTransporte;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $aumento;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $estado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $noupdate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroContrato(): ?string
    {
        return $this->numeroContrato;
    }

    public function setNumeroContrato(string $numeroContrato): self
    {
        $this->numeroContrato = $numeroContrato;

        return $this;
    }

    public function getNitTercero(): ?int
    {
        return $this->nitTercero;
    }

    public function setNitTercero(?int $nitTercero): self
    {
        $this->nitTercero = $nitTercero;

        return $this;
    }

    public function getIngreso(): ?\DateTimeInterface
    {
        return $this->ingreso;
    }

    public function setIngreso(?\DateTimeInterface $ingreso): self
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    public function getRetiro(): ?string
    {
        return $this->retiro;
    }

    public function setRetiro(?string $retiro): self
    {
        $this->retiro = $retiro;

        return $this;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(?string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCentroCosto(): ?string
    {
        return $this->centroCosto;
    }

    public function setCentroCosto(?string $centroCosto): self
    {
        $this->centroCosto = $centroCosto;

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

    public function getSueldoMes(): ?int
    {
        return $this->sueldoMes;
    }

    public function setSueldoMes(?int $sueldoMes): self
    {
        $this->sueldoMes = $sueldoMes;

        return $this;
    }

    public function getAuxilioTransporte(): ?int
    {
        return $this->auxilioTransporte;
    }

    public function setAuxilioTransporte(?int $auxilioTransporte): self
    {
        $this->auxilioTransporte = $auxilioTransporte;

        return $this;
    }

    public function getAumento(): ?\DateTimeInterface
    {
        return $this->aumento;
    }

    public function setAumento(?\DateTimeInterface $aumento): self
    {
        $this->aumento = $aumento;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

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
