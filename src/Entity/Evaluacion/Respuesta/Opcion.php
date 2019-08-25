<?php

namespace App\Entity\Evaluacion\Respuesta;

use App\Entity\Evaluacion\Pregunta\Opcion as PreguntaOpcion;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\Respuesta\OpcionRepository")
 * @ORM\Table(name="evaluacion_respuesta_opcion")
 */
class Opcion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Respuesta\MultipleUnica", inversedBy="opciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $respuesta;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Pregunta\Opcion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $preguntaOpcion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRespuesta(): ?Respuesta
    {
        return $this->respuesta;
    }

    public function setRespuesta(?Respuesta $respuesta): self
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    public function getPreguntaOpcion(): ?PreguntaOpcion
    {
        return $this->preguntaOpcion;
    }

    public function setPreguntaOpcion(?PreguntaOpcion $preguntaOpcion): self
    {
        $this->preguntaOpcion = $preguntaOpcion;

        return $this;
    }
}
