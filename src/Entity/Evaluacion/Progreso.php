<?php

namespace App\Entity\Evaluacion;

use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\ProgresoRepository")
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
    private $porcentajeCompletitud;

    /**
     * @ORM\Column(type="smallint")
     */
    private $porcentajeExito;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Modulo")
     */
    private $modulo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Diapositiva")
     */
    private $diapositiva;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Pregunta\Pregunta")
     */
    private $pregunta;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $descripcion;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
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

    public function getCulminacion(): ?\DateTimeInterface
    {
        return $this->culminacion;
    }

    public function setCulminacion(?\DateTimeInterface $culminacion): self
    {
        $this->culminacion = $culminacion;

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

    public function getPorcentajeExito(): ?int
    {
        return $this->porcentajeExito;
    }

    public function setPorcentajeExito(int $porcentajeExito): self
    {
        $this->porcentajeExito = $porcentajeExito;

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

    public function getDiapositiva(): ?Diapositiva
    {
        return $this->diapositiva;
    }

    public function setDiapositiva(?Diapositiva $diapositiva): self
    {
        $this->diapositiva = $diapositiva;

        return $this;
    }

    public function getPregunta(): ?Pregunta
    {
        return $this->pregunta;
    }

    public function setPregunta(?Pregunta $pregunta): self
    {
        $this->pregunta = $pregunta;

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
}
