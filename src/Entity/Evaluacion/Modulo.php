<?php

namespace App\Entity\Evaluacion;

use App\Entity\Evaluacion\Pregunta\Pregunta;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\ModuloRepository")
 * @ORM\Table(name="evaluacion_modulo")
 */
class Modulo extends HasDiapositivas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("selr:migrate")
     */
    private $id;

    /**
     * @var Evaluacion
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
     * @ORM\OrderBy({"indice" = "ASC"})
     */
    protected $diapositivas;

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
     * @ORM\OrderBy({"indice" = "ASC"})
     */
    private $preguntas;

    public function __construct()
    {
        parent::__construct();
        $this->preguntas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluacion(): Evaluacion
    {
        return $this->evaluacion;
    }

    public function setEvaluacion(Evaluacion $evaluacion): self
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

    public function getNumeroIntentos(): ?int
    {
        return $this->numeroIntentos;
    }

    public function setNumeroIntentos(int $numeroIntentos): self
    {
        $this->numeroIntentos = $numeroIntentos;

        return $this;
    }

    public function isRepetirEnFallo(): bool
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

    public function getNextPregunta(Pregunta $pregunta)
    {
        $index = $this->preguntas->indexOf($pregunta);
        if($index !== false && $index < $this->preguntas->count() - 1) {
            return $this->preguntas->get($index + 1);
        }
        return false;
    }

    public function getPrevPregunta(Pregunta $pregunta)
    {
        $index = $this->preguntas->indexOf($pregunta);
        if($index !== false && $index > 0) {
            return $this->preguntas->get($index - 1);
        }
        return false;
    }

    public function tienePreguntas()
    {
        return $this->evaluacion->isPreguntasEnabled() && $this->preguntas->count() > 0;
    }

    public function getPrimeraPregunta()
    {
        return $this->preguntas->first();
    }

    public function getUltimaPregunta()
    {
        return $this->preguntas->last();
    }

    public function isLastPregunta(Pregunta $pregunta)
    {
        return $this->preguntas->last() === $pregunta;
    }
}
