<?php

namespace App\Entity\Novasoft\Report\Clientes\ListadoNomina;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaRenglonRepository")
 * @ORM\Table(name="novasoft_listado_nomina_renglon")
 */
class ListadoNominaRenglon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="integer")
     */
    private $valor;

    /**
     * @ORM\ManyToOne(targetEntity="ListadoNominaSubgrupo", inversedBy="renglones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subgrupo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empleado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?float
    {
        return $this->cantidad;
    }

    public function setCantidad(float $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getSubgrupo(): ?ListadoNominaSubgrupo
    {
        return $this->subgrupo;
    }

    public function setSubgrupo(?ListadoNominaSubgrupo $subgrupo): self
    {
        $this->subgrupo = $subgrupo;

        return $this;
    }

    public function getEmpleado(): ?ListadoNominaEmpleado
    {
        return $this->empleado;
    }

    public function setEmpleado(?ListadoNominaEmpleado $empleado): self
    {
        $this->empleado = $empleado;

        return $this;
    }
}
