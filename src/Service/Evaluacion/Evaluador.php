<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use Exception;

class Evaluador
{
    /**
     * @var Progreso
     */
    private $progreso;

    public function __construct(Progreso $progreso)
    {
        $this->progreso = $progreso;
    }

    /**
     * @return Respuesta|false|null
     * @throws Exception
     */
    public function getRespuesta($original = false)
    {
        $respuesta = $this->progreso->getRespuesta();
        if(!$original) {
            if(!$respuesta) {
                $classRespuesta = str_replace('Pregunta', 'Respuesta', get_class($this->progreso->getPregunta()));
                $respuesta = (new $classRespuesta())->setPregunta($this->progreso->getPregunta());
            } else {
                $respuesta = clone $respuesta;
            }
        }

        return $respuesta;
    }

    /**
     * @param Pregunta $pregunta
     * @return Diapositiva|null
     * @throws Exception
     */
    public function getPreguntaDiapositiva(Pregunta $pregunta)
    {
        $respuesta = $this->progreso->getRespuesta($pregunta);
        if($respuesta) {
            if($pregunta->getDiapositivas() && !$respuesta->evaluar()) {
                return $pregunta->getDiapositivas()->first();
            }
        }
        return null;
    }

    public function updateProgreso(Respuesta $respuesta)
    {
        $pregunta = $respuesta->getPregunta();
        $porcentajeExito = $pregunta->getPorcentajeExito();
        // nueva respuesta
        if(!$respuesta->getProgreso()) {
            $this->progreso
                ->addRespuesta($respuesta)
                ->addPorcentajeCompletitud($porcentajeExito)
                ->addPorcentajeExito($respuesta->evaluar() ? $porcentajeExito : 0);
        }
        // ya respondida anteriormente
        else {
            $oldRespuesta = $this->progreso->getRespuesta($pregunta);
            $this->progreso
                ->restPorcentajeExito($oldRespuesta->evaluar() ? $porcentajeExito : 0)
                ->addPorcentajeExito($respuesta->evaluar() ? $porcentajeExito : 0)
                ->removeRespuesta($oldRespuesta)
                ->addRespuesta($respuesta);
        }
    }

    public function isPreguntaRepeticion()
    {
        $respuesta = $this->getRespuesta(true);
        return $respuesta !== null;
    }

    public function evaluarRespuesta()
    {
        if($respuesta = $this->getRespuesta(true)) {
            return $respuesta->evaluar();
        }
        return null;
    }

}