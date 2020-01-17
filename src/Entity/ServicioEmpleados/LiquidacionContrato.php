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
     * @ORM\Column(type="string", length=105, nullable=true)
     */
    protected $convenio;

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

    /**
     * @return mixed
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param mixed $convenio
     * @return LiquidacionContrato
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
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
