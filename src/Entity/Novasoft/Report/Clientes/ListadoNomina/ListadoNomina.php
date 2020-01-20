<?php

namespace App\Entity\Novasoft\Report\Clientes\ListadoNomina;

use App\Entity\Main\Convenio;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\Clientes\ListadoNominaRepository")
 * @ORM\Table(name="novasoft_listado_nomina")
 */
class ListadoNomina
{
    const TIPO_LIQUIDACION = [
        "01" => "Liquidación de Nómina",
        "02" => "Prima de Servicios",
        "04" => "Liquidacion de Contrato"
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Convenio")
     * @ORM\JoinColumn(name="convenio_codigo", referencedColumnName="codigo", nullable=false)
     */
    private $convenio;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $tipoLiquidacion;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaNomina;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado",
     *     mappedBy="listadoNomina",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $empleados;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupo",
     *     mappedBy="listadoNomina",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $grupos;

    public function __construct()
    {
        $this->empleados = new ArrayCollection();
        $this->grupos = new ArrayCollection();
    }

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

    public function getTipoLiquidacion(): ?string
    {
        return $this->tipoLiquidacion;
    }

    public function setTipoLiquidacion(string $tipoLiquidacion): self
    {
        $this->tipoLiquidacion = $tipoLiquidacion;

        return $this;
    }

    public function getFechaNomina(): ?DateTimeInterface
    {
        return $this->fechaNomina;
    }

    public function setFechaNomina(DateTimeInterface $fechaNomina): self
    {
        $this->fechaNomina = $fechaNomina;

        return $this;
    }

    /**
     * @return ArrayCollection|ListadoNominaEmpleado[]
     */
    public function getEmpleados()
    {
        return $this->empleados;
    }

    public function addEmpleado(ListadoNominaEmpleado $empleado): self
    {
        if (!$this->empleados->contains($empleado)) {
            $this->empleados[] = $empleado;
            $empleado->setListadoNomina($this);
        }

        return $this;
    }

    public function removeEmpleado(ListadoNominaEmpleado $empleado): self
    {
        if ($this->empleados->contains($empleado)) {
            $this->empleados->removeElement($empleado);
            // set the owning side to null (unless already changed)
            if ($empleado->getListadoNomina() === $this) {
                $empleado->setListadoNomina(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|ListadoNominaGrupo[]
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    public function addGrupo(ListadoNominaGrupo $grupo): self
    {
        if (!$this->grupos->contains($grupo)) {
            $this->grupos[] = $grupo;
            $grupo->setListadoNomina($this);
        }

        return $this;
    }

    public function removeGrupo(ListadoNominaGrupo $grupo): self
    {
        if ($this->grupos->contains($grupo)) {
            $this->grupos->removeElement($grupo);
            // set the owning side to null (unless already changed)
            if ($grupo->getListadoNomina() === $this) {
                $grupo->setListadoNomina(null);
            }
        }

        return $this;
    }

    public function compare(ListadoNomina $listadoNomina)
    {
        return $this->getConvenio()->getCodigo() === $listadoNomina->getConvenio()->getCodigo()
            && $this->getTipoLiquidacion() === $listadoNomina->getTipoLiquidacion()
            && $this->getFechaNomina()->format('Y-m-d') === $listadoNomina->getFechaNomina()->format('Y-m-d');
    }
}
