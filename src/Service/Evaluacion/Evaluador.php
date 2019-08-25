<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Progreso;
use App\Entity\Evaluacion\Respuesta\Respuesta;

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
     * @return Respuesta|false
     */
    public function getRespuesta()
    {
        $respuesta = $this->progreso->getRespuesta();
        if($respuesta !== false) {
            if(!$respuesta) {
                $classRespuesta = str_replace('Pregunta', 'Respuesta', get_class($this->progreso->getPregunta()));
                $respuesta = (new $classRespuesta())
                    ->setProgreso($this->progreso)
                    ->setPregunta($this->progreso->getPregunta());
            }
            return $respuesta;
        }
        return false;
    }
}