<?php

namespace App\Entity\ServicioEmpleados;

use App\Entity\Main\Usuario;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicioEmpleados\CertificadoIngresosRepository")
 * @ORM\Table(name="se_certificado_ingresos")
 */
class CertificadoIngresos extends ServicioEmpleadosReport
{
    /**
     * @ORM\Column(type="date")
     */
    private $periodo;

    /**
     * @ORM\Column(type="string", length=28)
     */
    protected $sourceId;

    public function getPeriodo(): ?DateTimeInterface
    {
        return $this->periodo;
    }

    public function setPeriodo(DateTimeInterface $periodo): self
    {
        $this->periodo = $periodo;

        return $this;
    }
}
