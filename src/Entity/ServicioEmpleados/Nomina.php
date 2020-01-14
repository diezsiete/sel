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
class Nomina extends ServicioEmpleadosReport
{
    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=105)
     */
    protected $convenio;

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
}
