<?php

namespace App\Entity\Evaluacion;

use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Usuario;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\EvaluacionRepository")
 */
class Evaluacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nombre;

    /**
     * @Gedmo\Slug(fields={"nombre"})
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint")
     */
    private $minimoPorcentajeExito;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $repetirEnFallo;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $guardarProceso = 1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Modulo", mappedBy="evaluacion", orphanRemoval=true)
     */
    private $modulos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Pregunta\Pregunta", mappedBy="evaluacion", orphanRemoval=true)
     */
    private $preguntas;

    public function __construct()
    {
        $this->modulos = new ArrayCollection();
        $this->preguntas = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMinimoPorcentajeExito(): ?int
    {
        return $this->minimoPorcentajeExito;
    }

    public function setMinimoPorcentajeExito(int $minimoPorcentajeExito): self
    {
        $this->minimoPorcentajeExito = $minimoPorcentajeExito;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRepetirEnFallo(): bool
    {
        return $this->repetirEnFallo;
    }

    /**
     * @param bool $repetirEnFallo
     * @return Evaluacion
     */
    public function setRepetirEnFallo(bool $repetirEnFallo): Evaluacion
    {
        $this->repetirEnFallo = $repetirEnFallo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGuardarProceso(): bool
    {
        return $this->guardarProceso;
    }

    /**
     * @param bool $guardarProceso
     * @return Evaluacion
     */
    public function setGuardarProceso(bool $guardarProceso): Evaluacion
    {
        $this->guardarProceso = $guardarProceso;
        return $this;
    }

    /**
     * @return Collection|Modulo[]
     */
    public function getModulos(): Collection
    {
        return $this->modulos;
    }

    public function addModulo(Modulo $modulo): self
    {
        if (!$this->modulos->contains($modulo)) {
            $this->modulos[] = $modulo;
            $modulo->setEvaluacion($this);
        }

        return $this;
    }

    public function removeModulo(Modulo $modulo): self
    {
        if ($this->modulos->contains($modulo)) {
            $this->modulos->removeElement($modulo);
            // set the owning side to null (unless already changed)
            if ($modulo->getEvaluacion() === $this) {
                $modulo->setEvaluacion(null);
            }
        }

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
            $pregunta->setEvaluacion($this);
        }

        return $this;
    }

    public function removePregunta(Pregunta $pregunta): self
    {
        if ($this->preguntas->contains($pregunta)) {
            $this->preguntas->removeElement($pregunta);
            // set the owning side to null (unless already changed)
            if ($pregunta->getEvaluacion() === $this) {
                $pregunta->setEvaluacion(null);
            }
        }

        return $this;
    }
}
