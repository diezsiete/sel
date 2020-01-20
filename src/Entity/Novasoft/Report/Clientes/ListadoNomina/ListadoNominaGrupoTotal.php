<?php

namespace App\Entity\Novasoft\Report\Clientes\ListadoNomina;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoTotalRepository")
 * @ORM\Table(name="novasoft_listado_nomina_grupo_total")
 */
class ListadoNominaGrupoTotal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $valor = 0;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $identificacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupo", inversedBy="totales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grupo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado")
     */
    private $empleado;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * @param mixed $identificacion
     * @return ListadoNominaGrupoTotal
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    public function getGrupo(): ?ListadoNominaGrupo
    {
        return $this->grupo;
    }

    public function setGrupo(?ListadoNominaGrupo $grupo): self
    {
        $this->grupo = $grupo;

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
