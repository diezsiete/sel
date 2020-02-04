<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use App\Repository\Evaluacion\Respuesta\RespuestaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

class Evaluador
{
    /**
     * @var Progreso
     */
    private $progreso;
    /**
     * @var RespuestaRepository
     */
    private $respuestaRepository;

    public function __construct(Progreso $progreso, RespuestaRepository $respuestaRepository)
    {
        $this->progreso = $progreso;
        $this->respuestaRepository = $respuestaRepository;
    }

    public function buildRespuesta()
    {
        $respuesta = $this->getRespuesta();
        if(!$respuesta) {
            $classRespuesta = str_replace('Pregunta', 'Respuesta', get_class($this->progreso->getPregunta()));
            $respuesta = (new $classRespuesta())->setPregunta($this->progreso->getPregunta());
        } else {
            $respuesta = clone $respuesta;
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
        $respuesta = $this->getRespuesta();
        if($respuesta) {
            if($pregunta->getDiapositivas()->count() && !$respuesta->evaluar()) {
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
            $oldRespuesta = $this->getRespuesta($pregunta);
            $oldPorcentaje = $oldRespuesta->evaluar() ? $porcentajeExito : 0;
            $porcentaje = $respuesta->evaluar() ? $porcentajeExito : 0;
            $this->progreso
                ->restPorcentajeExito($oldPorcentaje)
                ->restPorcentajeCompletitud($oldPorcentaje)
                ->addPorcentajeExito($porcentaje)
                ->addPorcentajeCompletitud($porcentaje)
                ->removeRespuesta($oldRespuesta)
                ->addRespuesta($respuesta);
        }

        if($this->getPreguntasContainer()->isLastPregunta($pregunta) && $this->progreso->getModulo()->isRepetirEnFallo()) {
            if(!$this->isModuloRepeticion()) {
                $isRepeticion = !$this->evaluarRespuestas($this->progreso->getRespuesta($this->progreso->getModulo()->getPreguntas()));
                $this->progreso->setModuloRepeticion($isRepeticion);
            }
        }
    }

    public function evaluarRespuesta()
    {
        if($respuesta = $this->getRespuesta()) {
            return $respuesta->evaluar();
        }
        return null;
    }

    public function isPreguntaRepeticion()
    {
        $respuesta = $this->getRespuesta();
        return $respuesta !== null;
    }

    public function isModuloRepeticion()
    {
        return $this->progreso->isModuloRepeticion();
    }

    /**
     * @param Modulo|null $modulo
     * @return ModuloRepeticionDecorator
     * @throws Exception
     */
    public function getModuloRepeticion(?Modulo $modulo = null)
    {
        $modulo = $modulo ?? $this->progreso->getModulo();
        $respuestas = $this->getRespuesta($modulo->getPreguntas());

        return new ModuloRepeticionDecorator($respuestas);
    }


    /**
     * @param Pregunta|Pregunta[]|null $pregunta
     * @return Respuesta|Respuesta[]|null
     * @throws NonUniqueResultException
     */
    private function getRespuesta($pregunta = null)
    {
        $pregunta = $pregunta ?? $this->progreso->getPregunta();
        return $this->respuestaRepository->getRespuesta($this->progreso, $pregunta);
    }

    /**
     * @param Respuesta[] $respuestas
     * @return bool
     */
    private function evaluarRespuestas($respuestas)
    {
        $allOk = true;
        foreach($respuestas as $respuesta) {
            if(!$respuesta->evaluar()) {
                $allOk = false;
            }
        }
        return $allOk;
    }

    public function evaluarModulo(?Modulo $modulo = null)
    {
        $modulo = $modulo ?? $this->progreso->getModulo();
        $preguntas = $modulo->getPreguntas();
        $respuestas = $this->getRespuesta($preguntas);
        return $this->evaluarRespuestas($respuestas);
    }

    /**
     * @return Modulo|ModuloRepeticionDecorator|null
     * @throws \Exception
     */
    private function getPreguntasContainer()
    {
        return $this->isModuloRepeticion() ? $this->getModuloRepeticion() : $this->progreso->getModulo();
    }
}