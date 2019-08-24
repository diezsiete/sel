<?php

namespace App\Entity\Evaluacion;

use App\Entity\Evaluacion\Pregunta\Pregunta;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\ModuloRepository")
 * @ORM\Table(name="evaluacion_modulo")
 */
class Modulo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Evaluacion", inversedBy="modulos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evaluacion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="smallint")
     */
    private $indice;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Evaluacion\Diapositiva")
     * @ORM\JoinTable(name="evaluacion_modulo_diapositiva")
     */
    private $diapositivas;

    /**
     * @ORM\Column(type="smallint")
     */
    private $numeroIntentos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $repetirEnFallo;

    /**
     * @Gedmo\Slug(fields={"nombre"})
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Pregunta\Pregunta", mappedBy="modulo")
     */
    private $preguntas;

    public function __construct()
    {
        $this->diapositivas = new ArrayCollection();
        $this->preguntas = new ArrayCollection();
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

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

    public function getNextDiapositiva(Diapositiva $diapositiva)
    {
        $index = $this->diapositivas->indexOf($diapositiva);
        if($index !== false && $index < $this->diapositivas->count() - 1) {
            return $this->diapositivas->get($index + 1);
        }
        return false;
    }

    public function getPrevDiapositiva(Diapositiva $diapositiva)
    {
        $index = $this->diapositivas->indexOf($diapositiva);
        if($index !== false && $index > 0) {
            return $this->diapositivas->get($index - 1);
        }
        return false;
    }

    public function getNumeroIntentos(): ?int
    {
        return $this->numeroIntentos;
    }

    public function setNumeroIntentos(int $numeroIntentos): self
    {
        $this->numeroIntentos = $numeroIntentos;

        return $this;
    }

    public function getRepetirEnFallo(): ?bool
    {
        return $this->repetirEnFallo;
    }

    public function setRepetirEnFallo(bool $repetirEnFallo): self
    {
        $this->repetirEnFallo = $repetirEnFallo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Modulo
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }


    /**
     * @return Collection|Pregunta[]
     */
    public function getPreguntas(): Collection
    {
        return $this->preguntas;
    }

    public function addPregunta(Pregunta $pregunta): self
    {
        if (!$this->preguntas->contains($pregunta)) {
            $this->preguntas[] = $pregunta;
            $pregunta->setModulo($this);
        }

        return $this;
    }

    public function removePregunta(Pregunta $pregunta): self
    {
        if ($this->preguntas->contains($pregunta)) {
            $this->preguntas->removeElement($pregunta);
            // set the owning side to null (unless already changed)
            if ($pregunta->getModulo() === $this) {
                $pregunta->setModulo(null);
            }
        }

        return $this;
    }
}
