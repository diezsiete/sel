<?php

namespace App\Entity\Evaluacion;

use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use App\Entity\Usuario;
use App\Repository\Evaluacion\Respuesta\RespuestaRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\ProgresoRepository")
 * @ORM\Table(name="evaluacion_progreso")
 */
class Progreso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Usuario", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @var Evaluacion
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Evaluacion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evaluacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $culminacion;

    /**
     * @ORM\Column(type="smallint")
     */
    private $porcentajeCompletitud = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $porcentajeExito = 0;

    /**
     * @var Modulo|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Modulo")
     */
    private $modulo;

    /**
     * @var Diapositiva|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Diapositiva")
     */
    private $diapositiva;

    /**
     * @var Pregunta|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Pregunta\Pregunta")
     */
    private $pregunta;

    /**
     * @var Diapositiva|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Diapositiva")
     */
    private $preguntaDiapositiva;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $descripcion = "Inicial";

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Respuesta\Respuesta", mappedBy="progreso", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $respuestas;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $moduloRepeticion = false;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $preguntasEnabled = true;

    public function __construct()
    {
        $this->respuestas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(UserInterface $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
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

    public function getCulminacion(): ?DateTimeInterface
    {
        return $this->culminacion;
    }

    public function setCulminacion(?DateTimeInterface $culminacion): self
    {
        $this->culminacion = $culminacion;
        $this->preguntasEnabled = $culminacion ? false : true;
        return $this;
    }

    public function getPorcentajeCompletitud(): ?int
    {
        return $this->porcentajeCompletitud;
    }

    public function setPorcentajeCompletitud(int $porcentajeCompletitud): self
    {
        $this->porcentajeCompletitud = $porcentajeCompletitud;

        return $this;
    }

    public function addPorcentajeCompletitud(int $porcentaje): self
    {
        $this->porcentajeCompletitud += $porcentaje;
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

    public function addPorcentajeExito(int $porcentajeExito): self
    {
        $this->porcentajeExito += $porcentajeExito;
        return $this;
    }
    public function restPorcentajeExito(int $porcentajeExito): self
    {
        $this->porcentajeExito -= $porcentajeExito;
        return $this;
    }

    public function getModulo(): ?Modulo
    {
        return $this->modulo;
    }

    public function setModulo(?Modulo $modulo): self
    {
        if(!$this->modulo || $this->modulo->getId() !== $modulo->getId()) {
            $this->modulo = $modulo;
            $this->moduloRepeticion = false;
        }

        return $this;
    }

    public function getDiapositiva(): ?Diapositiva
    {
        return $this->diapositiva;
    }

    public function setDiapositiva(Diapositiva $diapositiva): self
    {
        $this->diapositiva = $diapositiva;
        $this->pregunta = null;
        $this->preguntaDiapositiva = null;
        return $this;
    }

    public function getPregunta(): ?Pregunta
    {
        return $this->pregunta;
    }

    public function setPregunta(Pregunta $pregunta): self
    {
        $this->pregunta = $pregunta;
        $this->diapositiva = null;
        $this->preguntaDiapositiva = null;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPreguntaDiapositiva(): ?Diapositiva
    {
        return $this->preguntaDiapositiva;
    }

    public function setPreguntaDiapositiva(?Diapositiva $preguntaDiapositiva): self
    {
        $this->preguntaDiapositiva = $preguntaDiapositiva;

        return $this;
    }

    /**
     * @return bool
     */
    public function moduloTienePreguntas()
    {
        return $this->modulo ? $this->modulo->tienePreguntas() : false;
    }

    /**
     * @return Modulo|false
     */
    public function getNextModulo()
    {
        return $this->evaluacion->getNextModulo($this->modulo);
    }

    /**
     * @return Modulo|false
     */
    public function getPrevModulo()
    {
        return $this->evaluacion->getPrevModulo($this->modulo);
    }

    /**
     * @return Collection|Respuesta[]
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(Respuesta $respuesta): self
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas[] = $respuesta;
            $respuesta->setProgreso($this);
        }

        return $this;
    }

    public function removeRespuesta(Respuesta $respuesta): self
    {
        if ($this->respuestas->contains($respuesta)) {
            $this->respuestas->removeElement($respuesta);
            // set the owning side to null (unless already changed)
            if ($respuesta->getProgreso() === $this) {
                $respuesta->setProgreso(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isModuloRepeticion(): bool
    {
        return $this->moduloRepeticion;
    }

    /**
     * @param bool $moduloRepeticion
     * @return Progreso
     */
    public function setModuloRepeticion(bool $moduloRepeticion): Progreso
    {
        $this->moduloRepeticion = $moduloRepeticion;
        return $this;
    }


    /**
     * @param Pregunta|null|ArrayCollection<Pregunta> $pregunta
     * @return Respuesta|Respuesta[]
     * @throws Exception
     */
    public function getRespuesta($pregunta = null)
    {
        $pregunta = $pregunta ? $pregunta : $this->pregunta;
        if($pregunta) {
            $result = $this->respuestas->matching(RespuestaRepository::getByPreguntaCriteria($pregunta));
            if($pregunta instanceof Pregunta) {
                $result = $result->count() === 0 ? null : $result->first();
            }
            return $result;
        } else {
            throw new Exception("Asking for respuesta without pregunta");
        }
    }

    /**
     * @return bool
     */
    public function isPreguntasEnabled(): bool
    {
        return $this->preguntasEnabled;
    }

    /**
     * @param bool $preguntasEnabled
     * @return Progreso
     */
    public function setPreguntasEnabled(bool $preguntasEnabled): Progreso
    {
        $this->preguntasEnabled = $preguntasEnabled;
        return $this;
    }
}
