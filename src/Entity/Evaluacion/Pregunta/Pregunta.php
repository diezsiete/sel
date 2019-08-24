<?php

namespace App\Entity\Evaluacion\Pregunta;

use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\Pregunta\PreguntaRepository")
 * @ORM\Table(name="evaluacion_pregunta")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="widget", type="string")
 * @ORM\DiscriminatorMap({"MultipleUnica" = "MultipleUnica", "MultipleOrdenar": "MultipleOrdenar"})
 */
abstract class Pregunta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Evaluacion", inversedBy="preguntas")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $evaluacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Modulo", inversedBy="preguntas")
     */
    protected $modulo;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $indice;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $texto;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $porcentajeExito;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $numeroIntentos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Evaluacion\Diapositiva")
     * @ORM\JoinTable(name="evaluacion_pregunta_diapositiva")
     */
    protected $diapositivas;


    public function __construct()
    {
        $this->diapositivas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluacion(): ?Evaluacion
    {
        return $this->evaluacion;
    }

    public function setEvaluacion(?Evaluacion $evaluacion): self
    {
        $this->evaluacion = $evaluacion;

        return $this;
    }

    public function getModulo(): ?Modulo
    {
        return $this->modulo;
    }

    public function setModulo(?Modulo $modulo): self
    {
        $this->modulo = $modulo;

        return $this;
    }

    public function getIndice(): ?int
    {
        return $this->indice;
    }

    public function setIndice(int $indice): self
    {
        $this->indice = $indice;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getPorcentajeExito(): ?int
    {
        return $this->porcentajeExito;
    }

    public function setPorcentajeExito(int $porcentajeExito): self
    {
        $this->porcentajeExito = $porcentajeExito;

        return $this;
    }

    public function getNumeroIntentos(): ?int
    {
        return $this->numeroIntentos;
    }

    public function setNumeroIntentos(?int $numeroIntentos): self
    {
        $this->numeroIntentos = $numeroIntentos;

        return $this;
    }

    /**
     * @return Collection|Diapositiva[]
     */
    public function getDiapositivas(): Collection
    {
        return $this->diapositivas;
    }

    public function addDiapositiva(Diapositiva $diapositiva): self
    {
        if (!$this->diapositivas->contains($diapositiva)) {
            $this->diapositivas[] = $diapositiva;
        }

        return $this;
    }

    public function removeDiapositiva(Diapositiva $diapositiva): self
    {
        if ($this->diapositivas->contains($diapositiva)) {
            $this->diapositivas->removeElement($diapositiva);
        }

        return $this;
    }
}
