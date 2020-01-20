<?php

namespace App\Entity\Novasoft\Report\Clientes\ListadoNomina;

use App\Entity\Main\Empleado;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleadoRepository")
 * @ORM\Table(name="novasoft_listado_nomina_empleado")
 */
class ListadoNominaEmpleado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $identificacion;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $sucursalCodigo;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $sucursalNombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $contrato;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Empleado")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empleado;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $nombreCargo;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="integer")
     */
    private $sueldo;

    /**
     * @ORM\Column(type="float", nullable=true, precision=9, scale=4)
     */
    private $riesgoCargo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina", inversedBy="empleados")
     * @ORM\JoinColumn(nullable=false)
     */
    private $listadoNomina;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * @param mixed $identificacion
     * @return ListadoNominaEmpleado
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    public function getSucursalCodigo(): ?string
    {
        return $this->sucursalCodigo;
    }

    public function setSucursalCodigo(string $sucursalCodigo): self
    {
        $this->sucursalCodigo = $sucursalCodigo;

        return $this;
    }

    public function getSucursalNombre(): ?string
    {
        return $this->sucursalNombre;
    }

    public function setSucursalNombre(string $sucursalNombre): self
    {
        $this->sucursalNombre = $sucursalNombre;

        return $this;
    }

    public function getContrato(): ?int
    {
        return $this->contrato;
    }

    public function setContrato(int $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
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

    public function getNombreCargo(): ?string
    {
        return $this->nombreCargo;
    }

    public function setNombreCargo(string $nombreCargo): self
    {
        $this->nombreCargo = $nombreCargo;

        return $this;
    }

    public function getFechaIngreso(): ?\DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    public function setFechaIngreso(\DateTimeInterface $fechaIngreso): self
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    public function getSueldo(): ?int
    {
        return $this->sueldo;
    }

    public function setSueldo(int $sueldo): self
    {
        $this->sueldo = $sueldo;

        return $this;
    }

    public function getRiesgoCargo(): ?float
    {
        return $this->riesgoCargo;
    }

    public function setRiesgoCargo(?float $riesgoCargo): self
    {
        $this->riesgoCargo = $riesgoCargo;

        return $this;
    }

    public function getListadoNomina(): ?ListadoNomina
    {
        return $this->listadoNomina;
    }

    public function setListadoNomina(?ListadoNomina $listadoNomina): self
    {
        $this->listadoNomina = $listadoNomina;

        return $this;
    }
}
