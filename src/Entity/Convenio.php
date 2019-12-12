<?php

namespace App\Entity;

use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Novasoft\Report\TrabajadorActivo;
use App\Repository\EmpleadoRepository;
use App\Repository\RepresentanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConvenioRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Convenio implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=45)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $codigoCliente;

    /**
     * @ORM\Column(type="string", length=105)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=145, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Empleado", mappedBy="convenio")
     */
    private $empleados;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ssrsDb;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Autoliquidacion\Autoliquidacion", mappedBy="convenio")
     */
    private $autoliquidaciones;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Representante", mappedBy="convenio", orphanRemoval=true)
     */
    private $representantes;

    private $representantesByType = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Novasoft\Report\TrabajadorActivo", mappedBy="convenio", orphanRemoval=true)
     */
    private $trabajadoresActivos;


    public function __construct()
    {
        $this->empleados = new ArrayCollection();
        $this->autoliquidaciones = new ArrayCollection();
        $this->representantes = new ArrayCollection();
        $this->trabajadoresActivos = new ArrayCollection();
    }


    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getCodigoCliente(): ?string
    {
        return $this->codigoCliente;
    }

    public function setCodigoCliente(?string $codigoCliente): self
    {
        $this->codigoCliente = $codigoCliente;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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

    /**
     * @return Collection|Empleado[]
     */
    public function getEmpleados(): Collection
    {
        return $this->empleados;
    }

    public function getEmpleadosEnRango(\DateTimeInterface $fechaIngreso, \DateTimeInterface $fechaRetiro)
    {
        return $this->empleados->matching(EmpleadoRepository::rangoPeriodoCriteria($fechaIngreso, $fechaRetiro));
    }

    public function addEmpleado(Empleado $empleado): self
    {
        if (!$this->empleados->contains($empleado)) {
            $this->empleados[] = $empleado;
            $empleado->setConvenio($this);
        }

        return $this;
    }

    public function removeEmpleado(Empleado $empleado): self
    {
        if ($this->empleados->contains($empleado)) {
            $this->empleados->removeElement($empleado);
            // set the owning side to null (unless already changed)
            if ($empleado->getConvenio() === $this) {
                $empleado->setConvenio(null);
            }
        }

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
     * @return Convenio
     */
    public function setSsrsDb($ssrsDb)
    {
        $this->ssrsDb = $ssrsDb;
        return $this;
    }

    /**
     * @return Collection|Autoliquidacion[]
     */
    public function getAutoliquidaciones(): Collection
    {
        return $this->autoliquidaciones;
    }

    /**
     * @return Collection|Representante[]
     */
    public function getRepresentantes($type = null): Collection
    {
        if($type) {
            if(!isset($this->representantesByType[$type])) {
                $this->representantesByType[$type] = new ArrayCollection();
                foreach($this->getRepresentantes() as $representante) {
                    if ($representante->isType($type)) {
                        $this->representantesByType[$type]->add($representante);
                    }
                }
            }
            return $this->representantesByType[$type];
        } else {
            return $this->representantes;
        }
    }

    public function addRepresentante(Representante $representante): self
    {
        if (!$this->representantes->contains($representante)) {
            $this->representantes[] = $representante;
            $representante->setConvenio($this);
        }

        return $this;
    }

    public function removeRepresentante(Representante $representante): self
    {
        if ($this->representantes->contains($representante)) {
            $this->representantes->removeElement($representante);
            // set the owning side to null (unless already changed)
            if ($representante->getConvenio() === $this) {
                $representante->setConvenio(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Representante[]
     */
    public function getEncargados()
    {
        return $this->representantes->matching(RepresentanteRepository::encargadoCriteria());
    }

    /**
     * @return bool
     */
    public function hasEncargados()
    {
        return $this->getEncargados()->count() > 0;
    }

    /**
     * @return ArrayCollection|Representante[]|string[]
     */
    public function getBcc($emails = false)
    {
        $bccs = $this->representantes->matching(RepresentanteRepository::bccCriteria());
        if($emails) {
            $bccs = array_map(function (Representante $item) {
                return $item->getEmail();
            }, $bccs->toArray());
        }
        return $bccs;
    }

    /**
     * @ORM\PreFlush
     */
    public function preFlush()
    {
        $this->representantesByType = [];
    }


    public function jsonSerialize()
    {
        return [
            "codigo" => $this->codigo,
            "nombre" => $this->nombre,
            "codigoCliente" => $this->codigoCliente,
            "direccion" => $this->direccion
        ];
    }

    /**
     * @return Collection|TrabajadorActivo[]
     */
    public function getTrabajadoresActivos(): Collection
    {
        return $this->trabajadoresActivos;
    }

    public function addTrabajadoresActivo(TrabajadorActivo $trabajadoresActivo): self
    {
        if (!$this->trabajadoresActivos->contains($trabajadoresActivo)) {
            $this->trabajadoresActivos[] = $trabajadoresActivo;
            $trabajadoresActivo->setConvenio($this);
        }

        return $this;
    }

    public function removeTrabajadoresActivo(TrabajadorActivo $trabajadoresActivo): self
    {
        if ($this->trabajadoresActivos->contains($trabajadoresActivo)) {
            $this->trabajadoresActivos->removeElement($trabajadoresActivo);
            // set the owning side to null (unless already changed)
            if ($trabajadoresActivo->getConvenio() === $this) {
                $trabajadoresActivo->setConvenio(null);
            }
        }

        return $this;
    }
}
