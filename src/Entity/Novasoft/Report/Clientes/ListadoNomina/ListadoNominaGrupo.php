<?php

namespace App\Entity\Novasoft\Report\Clientes\ListadoNomina;

use App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoTotalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoRepository")
 * @ORM\Table(name="novasoft_listado_nomina_grupo")
 */
class ListadoNominaGrupo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $valorTotal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina", inversedBy="grupos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $listadoNomina;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoTotal",
     *     mappedBy="grupo",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $totales;

    /**
     * @ORM\OneToMany(
     *     targetEntity="ListadoNominaSubgrupo",
     *     mappedBy="grupo",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $subgrupos;

    public function __construct()
    {
        $this->totales = new ArrayCollection();
        $this->subgrupos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValorTotal(): ?int
    {
        return $this->valorTotal;
    }

    public function setValorTotal($valorTotal): self
    {
        $this->valorTotal = $valorTotal;

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

    /**
     * @return ArrayCollection|ListadoNominaGrupoTotal[]
     */
    public function getTotales()
    {
        return $this->totales;
    }

    public function addTotal(ListadoNominaGrupoTotal $total): self
    {
        if (!$this->totales->contains($total)) {
            $this->totales[] = $total;
            $total->setGrupo($this);
        }

        return $this;
    }

    public function removeTotal(ListadoNominaGrupoTotal $total): self
    {
        if ($this->totales->contains($total)) {
            $this->totales->removeElement($total);
            // set the owning side to null (unless already changed)
            if ($total->getGrupo() === $this) {
                $total->setGrupo(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|ListadoNominaSubgrupo[]
     */
    public function getSubgrupos()
    {
        return $this->subgrupos;
    }

    public function addSubgrupo(ListadoNominaSubgrupo $subgrupo): self
    {
        if (!$this->subgrupos->contains($subgrupo)) {
            $this->subgrupos[] = $subgrupo;
            $subgrupo->setGrupo($this);
        }

        return $this;
    }

    public function removeSubgrupo(ListadoNominaSubgrupo $subgrupo): self
    {
        if ($this->subgrupos->contains($subgrupo)) {
            $this->subgrupos->removeElement($subgrupo);
            // set the owning side to null (unless already changed)
            if ($subgrupo->getGrupo() === $this) {
                $subgrupo->setGrupo(null);
            }
        }

        return $this;
    }

    public function getTotalEmpleado($identificacion)
    {
        $filter = $this->totales->matching(ListadoNominaGrupoTotalRepository::filterByIdentCriteria($identificacion));
        return $filter->count() ? $filter->first() : new ListadoNominaGrupoTotal();
    }
}
