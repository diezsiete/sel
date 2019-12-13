<?php

namespace App\Entity;

use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmpleadoRepository")
 */
class Empleado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $sexo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $hijos;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telefono1;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telefono2;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $centroCosto;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(type="string", length=65, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio", inversedBy="empleados", cascade={"remove"})
     * @ORM\JoinColumn(name="convenio_codigo", referencedColumnName="codigo")
     */
    private $convenio;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ssrsDb;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Autoliquidacion\AutoliquidacionEmpleado", mappedBy="empleado", orphanRemoval=true)
     */
    private $autoliquidaciones;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Representante", inversedBy="empleados")
     */
    private $representante;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina", mappedBy="empleado", orphanRemoval=true)
     */
    private $liquidacionesNomina;

    public function __construct()
    {
        $this->autoliquidaciones = new ArrayCollection();
        $this->liquidacionesNomina = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSexo()
    {
        return $this->sexo;
    }

    public function setSexo($sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getEstadoCivil(): ?string
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(?string $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    public function getHijos(): ?int
    {
        return $this->hijos;
    }

    public function setHijos(?int $hijos): self
    {
        $this->hijos = $hijos;

        return $this;
    }

    public function getNacimiento(): ?DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(?DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getTelefono1(): ?string
    {
        return $this->telefono1;
    }

    public function setTelefono1(?string $telefono1): self
    {
        $this->telefono1 = $telefono1;

        return $this;
    }

    public function getTelefono2(): ?string
    {
        return $this->telefono2;
    }

    public function setTelefono2(?string $telefono2): self
    {
        $this->telefono2 = $telefono2;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }


    public function getCentroCosto(): ?string
    {
        return $this->centroCosto;
    }

    public function setCentroCosto(?string $centroCosto): self
    {
        $this->centroCosto = $centroCosto;

        return $this;
    }

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

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(?string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSsrsDb()
    {
        return $this->ssrsDb;
    }

    /**
     * @param mixed $ssrsDb
     * @return Empleado
     */
    public function setSsrsDb($ssrsDb)
    {
        $this->ssrsDb = $ssrsDb;
        return $this;
    }

    public function addAutoliquidacion(AutoliquidacionEmpleado $autoliquidacion): self
    {
        if (!$this->autoliquidaciones->contains($autoliquidacion)) {
            $this->autoliquidaciones[] = $autoliquidacion;
            $autoliquidacion->setEmpleado($this);
        }

        return $this;
    }

    public function removeAutoliquidacion(AutoliquidacionEmpleado $autoliquidacion): self
    {
        if ($this->autoliquidaciones->contains($autoliquidacion)) {
            $this->autoliquidaciones->removeElement($autoliquidacion);
            // set the owning side to null (unless already changed)
            if ($autoliquidacion->getEmpleado() === $this) {
                $autoliquidacion->setEmpleado(null);
            }
        }

        return $this;
    }

    public function getRepresentante(): ?Representante
    {
        return $this->representante;
    }

    public function setRepresentante(?Representante $representante): self
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * @return Collection|LiquidacionNomina[]
     */
    public function getLiquidacionesNomina(): Collection
    {
        return $this->liquidacionesNomina;
    }

    public function addLiquidacionesNomina(LiquidacionNomina $liquidacionesNomina): self
    {
        if (!$this->liquidacionesNomina->contains($liquidacionesNomina)) {
            $this->liquidacionesNomina[] = $liquidacionesNomina;
            $liquidacionesNomina->setEmpleado($this);
        }

        return $this;
    }

    public function removeLiquidacionesNomina(LiquidacionNomina $liquidacionesNomina): self
    {
        if ($this->liquidacionesNomina->contains($liquidacionesNomina)) {
            $this->liquidacionesNomina->removeElement($liquidacionesNomina);
            // set the owning side to null (unless already changed)
            if ($liquidacionesNomina->getEmpleado() === $this) {
                $liquidacionesNomina->setEmpleado(null);
            }
        }

        return $this;
    }

}
