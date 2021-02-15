<?php

namespace App\Entity\Evaluacion\Pregunta;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\Pregunta\OpcionRepository")
 * @ORM\Table(name="evaluacion_pregunta_opcion")
 */
class Opcion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("selr:migrate")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $texto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Pregunta\MultipleUnica", inversedBy="opciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pregunta;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $respuesta;

    /**
     * @var int
     */
    private $indice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

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

    public function getRespuesta(): ?int
    {
        return $this->respuesta;
    }

    public function setRespuesta(?int $respuesta): self
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    public function __toString()
    {
        return $this->texto;
    }

    /**
     * @return int
     */
    public function getIndice(): int
    {
        return $this->indice;
    }

    /**
     * @param int $indice
     * @return Opcion
     */
    public function setIndice(int $indice): Opcion
    {
        $this->indice = $indice;
        return $this;
    }


}
