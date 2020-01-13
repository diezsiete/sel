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
    private $noContrat;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $nitTercer;

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
    private $centroCos;

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
    private $auxTransp;

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

    public function getNoContrat(): ?string
    {
        return $this->noContrat;
    }

    public function setNoContrat(string $noContrat): self
    {
        $this->noContrat = $noContrat;

        return $this;
    }

    public function getNitTercer(): ?int
    {
        return $this->nitTercer;
    }

    public function setNitTercer(?int $nitTercer): self
    {
        $this->nitTercer = $nitTercer;

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

    public function getCentroCos(): ?string
    {
        return $this->centroCos;
    }

    public function setCentroCos(?string $centroCos): self
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

    public function getSueldoMes(): ?int
    {
        return $this->sueldoMes;
    }

    public function setSueldoMes(?int $sueldoMes): self
    {
        $this->sueldoMes = $sueldoMes;

        return $this;
    }

    public function getAuxTransp(): ?int
    {
        return $this->auxTransp;
    }

    public function setAuxTransp(?int $auxTransp): self
    {
        $this->auxTransp = $auxTransp;

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
