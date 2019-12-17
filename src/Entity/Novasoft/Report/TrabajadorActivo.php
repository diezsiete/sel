<?php

namespace App\Entity\Novasoft\Report;

use App\Entity\Convenio;
use App\Entity\Empleado;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\TrabajadorActivoRepository")
 */
class TrabajadorActivo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio", inversedBy="trabajadoresActivos")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="codigo")
     */
    private $convenio;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Empleado")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empleado;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ingresoBasico;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labor;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $porcentajeRiesgo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $caja;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $promotoraSalud;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $adminPension;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\CentroCosto", inversedBy="trabajadoresActivos")
     * @ORM\JoinColumn(referencedColumnName="codigo")
     */
    private $centroCosto;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConvenio(): ?Convenio
    {
        return $this->convenio;
    }

    public function setConvenio(?Convenio $convenio): self
    {
        $this->convenio = $convenio;

        return $this;
    }

    public function getEmpleado(): ?Empleado
    {
        return $this->empleado;
    }

    public function setEmpleado(Empleado $empleado): self
    {
        $this->empleado = $empleado;

        return $this;
    }

    public function getFechaIngreso(): ?DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    public function setFechaIngreso(?DateTimeInterface $fechaIngreso): self
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    public function getIngresoBasico(): ?int
    {
        return $this->ingresoBasico;
    }

    public function setIngresoBasico(?int $ingresoBasico): self
    {
        $this->ingresoBasico = $ingresoBasico;

        return $this;
    }

    public function getLabor(): ?string
    {
        return $this->labor;
    }

    public function setLabor(?string $labor): self
    {
        $this->labor = $labor;

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

    public function getPorcentajeRiesgo(): ?float
    {
        return $this->porcentajeRiesgo;
    }

    public function setPorcentajeRiesgo(?float $porcentajeRiesgo): self
    {
        $this->porcentajeRiesgo = $porcentajeRiesgo;

        return $this;
    }

    public function getCuenta(): ?string
    {
        return $this->cuenta;
    }

    public function setCuenta(?string $cuenta): self
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    public function getCaja(): ?string
    {
        return $this->caja;
    }

    public function setCaja(?string $caja): self
    {
        $this->caja = $caja;

        return $this;
    }

    public function getPromotoraSalud(): ?string
    {
        return $this->promotoraSalud;
    }

    public function setPromotoraSalud(?string $promotoraSalud): self
    {
        $this->promotoraSalud = $promotoraSalud;

        return $this;
    }

    public function getAdminPension(): ?string
    {
        return $this->adminPension;
    }

    public function setAdminPension(?string $adminPension): self
    {
        $this->adminPension = $adminPension;

        return $this;
    }

    public function getCentroCosto(): ?CentroCosto
    {
        return $this->centroCosto;
    }

    public function setCentroCosto(?CentroCosto $centroCosto): self
    {
        $this->centroCosto = $centroCosto;

        return $this;
    }
}
