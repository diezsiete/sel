<?php

namespace App\Entity\Novasoft\Report\Clientes\ListadoNomina;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaSubGrupoRepository")
 * @ORM\Table(name="novasoft_listado_nomina_subgrupo")
 */
class ListadoNominaSubgrupo
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
    private $cantidadTotal;

    /**
     * @ORM\Column(type="integer")
     */
    private $valorTotal;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupo", inversedBy="subgrupos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grupo;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaRenglon",
     *     mappedBy="subgrupo",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $renglones;

    public function __construct()
    {
        $this->renglones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidadTotal(): ?float
    {
        return $this->cantidadTotal;
    }

    public function setCantidadTotal(float $cantidadTotal): self
    {
        $this->cantidadTotal = $cantidadTotal;

        return $this;
    }

    public function getValorTotal(): ?int
    {
        return $this->valorTotal;
    }

    public function setValorTotal($valorTotal): self
    {
        $this->valorTotal = $valorTotal;

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

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

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

    /**
     * @return Collection|ListadoNominaRenglon[]
     */
    public function getRenglones(): Collection
    {
        return $this->renglones;
    }

    public function addRenglon(ListadoNominaRenglon $renglon): self
    {
        if (!$this->renglones->contains($renglon)) {
            $this->renglones[] = $renglon;
            $renglon->setSubGrupo($this);
        }

        return $this;
    }

    public function removeRenglon(ListadoNominaRenglon $renglon): self
    {
        if ($this->renglones->contains($renglon)) {
            $this->renglones->removeElement($renglon);
            // set the owning side to null (unless already changed)
            if ($renglon->getSubGrupo() === $this) {
                $renglon->setSubGrupo(null);
            }
        }

        return $this;
    }

    public function getRenglonEmpleado($identificacion)
    {
        $filter = $this->renglones->filter(function (ListadoNominaRenglon $renglon) use ($identificacion) {
            return $renglon->getEmpleado()->getIdentificacion() === $identificacion;
        });
        return $filter->count() === 1 ? $filter->first() : new ListadoNominaRenglon();
    }
}
