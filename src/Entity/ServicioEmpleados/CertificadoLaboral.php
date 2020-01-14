<?php

namespace App\Entity\ServicioEmpleados;

use App\Entity\Main\Usuario;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicioEmpleados\CertificadoLaboralRepository")
 * @ORM\Table(name="se_certificado_laboral")
 */
class CertificadoLaboral extends ServicioEmpleadosReport
{
    /**
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(type="string", length=105, nullable=true)
     */
    private $convenio;


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

    public function setFechaRetiro(?DateTimeInterface $fechaRetiro): self
    {
        $this->fechaRetiro = $fechaRetiro;

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
}
