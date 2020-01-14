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
