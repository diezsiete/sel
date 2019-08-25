<?php

namespace App\Entity\Evaluacion\Respuesta;

use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\Respuesta\RespuestaRepository")
 * @ORM\Table(name="evaluacion_respuesta")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="widget", type="string")
 * @ORM\DiscriminatorMap({"MultipleUnica" = "MultipleUnica", "MultipleOrdenar": "MultipleOrdenar"})
 */
abstract class Respuesta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Progreso", inversedBy="respuestas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $progreso;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Pregunta\Pregunta")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pregunta;

    /**
     * @ORM\Column(type="datetime")
     */
    private $respondidaEn;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgreso(): ?Progreso
    {
        return $this->progreso;
    }

    public function setProgreso(?Progreso $progreso): self
    {
        $this->progreso = $progreso;

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

    public function getRespondidaEn(): ?\DateTimeInterface
    {
        return $this->respondidaEn;
    }

    public function setRespondidaEn(\DateTimeInterface $respondidaEn): self
    {
        $this->respondidaEn = $respondidaEn;

        return $this;
    }
}
