<?php


namespace App\Entity\Evaluacion\Respuesta;

use App\Entity\Evaluacion\Pregunta\MultipleOrdenar as PreguntaMultipleOrdenar;
use App\Validator\EvaluacionMultipleOrdenar;
use Countable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MultipleOrdenar
 * @package App\Entity\Evaluacion\Respuesta
 * @ORM\Entity()
 * @method PreguntaMultipleOrdenar getPregunta()
 * @EvaluacionMultipleOrdenar()
 */
class MultipleOrdenar extends MultipleUnica implements Countable
{
    /**
     * @var Opcion[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Respuesta\Opcion", mappedBy="respuesta", orphanRemoval=true, cascade={"persist"})
     */
    protected $opciones;

    public function __get($name)
    {
        if(preg_match('/opcionPregunta(\d?)/', $name, $groups)) {
            return $this->getPreguntaOpcionByOrden((int) $groups[1]);
        }
    }

    public function __set($name, $value)
    {
        if(preg_match('/opcionPregunta(\d?)/', $name, $groups)) {
            $returnOpcion = $this->getPregunta()->getOpcionByIndice($value);
            $this->opciones->add((new Opcion())
                ->setPreguntaOpcion($returnOpcion)
                ->setRespuesta($this));
        }
    }

    /**
     * @param $orden
     * @return Opcion
     */
    public function getPreguntaOpcionByOrden($orden)
    {
        if($this->opciones->count() > 0) {
            for ($i = 0; $i < $this->opciones->count(); $i++) {
                $opcion = $this->opciones->get($i)->getPreguntaOpcion();
                if ($orden === $opcion->getRespuesta()) {
                    $opcion->setIndice($i + 1);
                    return $opcion;
                }
            }
        } else {
            $opciones = $this->getPregunta()->getOpciones();
            for ($i = 0; $i < $opciones->count(); $i++) {
                $opcion = $opciones->get($i);
                if ($orden === $opcion->getRespuesta()) {
                    $opcion->setIndice($i + 1);
                    return $opcion;
                }
            }
        }
    }

    public function evaluar(): bool
    {
        $ok = true;
        $prevRespuesta = 0;
        foreach($this->opciones as $opcion) {
            $respuesta = $opcion->getPreguntaOpcion()->getRespuesta();
            if($respuesta > $prevRespuesta) {
                $prevRespuesta = $respuesta;
            } else {
                $ok = false;
            }
        }
        return $ok;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->opciones->count();
    }
}