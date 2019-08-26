<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use Doctrine\Common\Collections\ArrayCollection;

class ModuloRepeticionDecorator
{
    /**
     * @var Respuesta[]|ArrayCollection
     */
    private $respuestas;

    public function __construct($respuestas)
    {
        $this->respuestas = $respuestas;
    }

    public function getPrimeraPregunta()
    {
        foreach($this->respuestas as $respuesta) {
            if(!$respuesta->evaluar()) {
                return $respuesta->getPregunta();
            }
        }
        return false;
    }

    public function getNextPregunta(Pregunta $pregunta)
    {
        $next = false;
        foreach($this->respuestas as $respuesta) {
            if($next && !$respuesta->evaluar()) {
                return $respuesta->getPregunta();
            }
            if($respuesta->getPregunta() === $pregunta){
                $next = true;
            }
        }
        return false;
    }

    public function getPrevPregunta(Pregunta $pregunta)
    {
        $prev = false;
        for($i = count($this->respuestas) - 1; $i <= 0; $i--) {
            if($prev && !$this->respuestas[$i]->evaluar()) {
                return $this->respuestas[$i]->getPregunta();
            }
            if($this->respuestas[$i]->getPregunta() === $pregunta) {
                $prev = true;
            }
        }
        return false;
    }

    public function isLastPregunta(Pregunta $pregunta)
    {
        $isLast = false;
        foreach($this->respuestas as $respuesta) {
            if($isLast && !$respuesta->evaluar()) {
                $isLast = false;
            }
            if($respuesta->getPregunta() === $pregunta) {
                $isLast = true;
            }
        }
        return $isLast;
    }

}