<?php

namespace App\Entity\ServicioEmpleados;

use App\Entity\Main\Usuario;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicioEmpleados\LiquidacionContratoRepository")
 * @ORM\Table(name="se_liquidacion_contrato")
 */
class LiquidacionContrato extends ServicioEmpleadosReport
{

    /**
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $contrato;

    public function getFechaIngreso(): ?DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    public function setFechaIngreso(DateTimeInterface $fechaIngreso): self
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    public function getFechaRetiro(): ?DateTimeInterface
    {
        return $this->fechaRetiro;
    }

    public function setFechaRetiro(DateTimeInterface $fechaRetiro): self
    {
        $this->fechaRetiro = $fechaRetiro;

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

    public function getContrato(): ?string
    {
        return $this->contrato;
    }

    public function setContrato(?string $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }
}
