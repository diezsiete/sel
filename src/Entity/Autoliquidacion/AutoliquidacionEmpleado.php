<?php

namespace App\Entity\Autoliquidacion;

use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository")
 */
class AutoliquidacionEmpleado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Empleado
     * @ORM\ManyToOne(targetEntity="App\Entity\Empleado", inversedBy="autoliquidaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empleado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Autoliquidacion\Autoliquidacion", inversedBy="empleados")
     * @ORM\JoinColumn(nullable=false)
     * @var Autoliquidacion
     */
    private $autoliquidacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exito = false;

    /**
     * @ORM\Column(type="string", length=145, nullable=true)
     */
    private $salida;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmpleado(): ?Empleado
    {
        return $this->empleado;
    }

    public function setEmpleado(?Empleado $empleado): self
    {
        $this->empleado = $empleado;

        return $this;
    }

    public function getAutoliquidacion(): Autoliquidacion
    {
        return $this->autoliquidacion;
    }

    public function setAutoliquidacion(Autoliquidacion $autoliquidacion): self
    {
        $this->autoliquidacion = $autoliquidacion;

        return $this;
    }

    public function isExito(): ?bool
    {
        return $this->exito;
    }

    public function setExito(bool $exito): self
    {
        $this->exito = $exito;

        return $this;
    }

    public function getSalida(): ?string
    {
        return $this->salida;
    }

    public function setSalida(?string $salida): self
    {
        $this->salida = $salida;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->empleado->getUsuario();
    }

    /**
     * @return Convenio
     */
    public function getConvenio()
    {
        return $this->autoliquidacion->getConvenio();
    }
}
