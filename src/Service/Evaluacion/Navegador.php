<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\RouterInterface;

class Navegador
{
    /**
     * @var Progreso
     */
    private $progreso;

    /**
     * @var Evaluador
     */
    private $evaluador;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Evaluacion
     */
    private $evaluacion;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function setEvaluacion(Evaluacion $evaluacion)
    {
        $this->evaluacion = $evaluacion;
        return $this;
    }

    public function setProgreso(Progreso $progreso)
    {
        $this->progreso = $progreso;
        return $this;
    }

    public function setEvaluador(Evaluador $evaluador)
    {
        $this->evaluador = $evaluador;
        return $this;
    }

    public function setEm(EntityManagerInterface $em)
    {
        $this->em = $em;
        return $this;
    }

    public function setRouteDiapositiva(Modulo $modulo, Diapositiva $diapositiva)
    {
        if(!$this->progreso->getDiapositiva() || $this->progreso->getDiapositiva()->getId() !== $diapositiva->getId()) {
            $this->checkAccess($modulo, $diapositiva);
            $diapositiva instanceof Pregunta
                ? $this->flushProgreso($modulo, null, $diapositiva)
                : $this->flushProgreso($modulo, $diapositiva);
        }
    }

    public function setRoutePregunta(Modulo $modulo, Pregunta $pregunta)
    {
        $progreso = $this->progreso;
        if(!$progreso->getPregunta() || $progreso->getPregunta()->getId() !== $pregunta->getId() || $progreso->getPreguntaDiapositiva()) {
            $this->checkAccess($modulo, $pregunta);
            $pregunta instanceof Diapositiva
                ? $this->flushProgreso($modulo, $pregunta)
                : $this->flushProgreso($modulo, null, $pregunta);
        }
    }

    public function setRoutePreguntaDiapositiva(Modulo $modulo, Pregunta $pregunta, Diapositiva $preguntaDiapositiva)
    {
        $progreso = $this->progreso;
        if(!$progreso->getPreguntaDiapositiva() || $progreso->getPreguntaDiapositiva()->getId() !== $preguntaDiapositiva->getId()) {
            $this->checkAccess($modulo, $pregunta, $preguntaDiapositiva);
            $pregunta instanceof Diapositiva
                ? $this->flushProgreso($modulo, $pregunta)
                : $this->flushProgreso($modulo, null, $pregunta, $preguntaDiapositiva);
        }
    }

    public function getCurrentRoute()
    {
        list($modulo, $diapositivaOPregunta, $preguntaDiapositiva) = $this->buildCurentRoute();
        return $this->buildRoute($modulo, $diapositivaOPregunta, $preguntaDiapositiva);
    }

    /**
     * @return bool
     */
    public function hasNextRoute()
    {
        if($this->progreso->getDiapositiva()) {
            // si es ultima diapositiva del modulo
            if($this->progreso->getModulo()->isLastDiapositiva($this->progreso->getDiapositiva())) {
                // si el modulo tiene preguntas o no es el ultimo modulo, hasNextRoute
                return
                    $this->progreso->moduloTienePreguntas() ||
                    !$this->progreso->getEvaluacion()->isLastModulo($this->progreso->getModulo());
            }
            return true;
        } else {
            // valido para induccion
            return true;
        }
    }

    public function getNextRoute()
    {
        if($this->hasNextRoute()) {
            list($modulo, $diapositivaOPregunta, $preguntaDiapositiva) = $this->buildNextRoute();
            return $this->buildRoute($modulo, $diapositivaOPregunta, $preguntaDiapositiva);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasPrevRoute()
    {
        if($this->progreso->getDiapositiva()) {
            if($this->progreso->isModuloRepeticion()) {
                return !$this->getModulo()->isFirstDiapositiva($this->progreso->getDiapositiva());
            }
            return $this->progreso->getDiapositiva()->getIndice() > 1;
        } else {
            if($this->progreso->getPreguntaDiapositiva()) {
                return !$this->progreso->getPregunta()->isFirstDiapositiva($this->progreso->getPreguntaDiapositiva());
            } else {
                return true;
            }
        }
    }

    public function getPrevRoute()
    {
        if($this->hasPrevRoute()) {
            if ($this->progreso->getDiapositiva()) {
                $diapositiva = $this->progreso->getModulo()->getPrevDiapositiva($this->progreso->getDiapositiva());
                if($diapositiva) {
                    return $this->buildRoute($this->progreso->getModulo(), $diapositiva);
                } else {
                    $prevModulo = $this->progreso->getPrevModulo();
                    return $this->buildRoute($prevModulo, $prevModulo->tienePreguntas()
                        ? $prevModulo->getUltimaPregunta() : $prevModulo->getUltimaDiapositiva());
                }
            } else {
                if($this->progreso->getPreguntaDiapositiva()) {
                    if($prevDiapositiva = $this->progreso->getPregunta()->getPrevDiapositiva($this->progreso->getPreguntaDiapositiva())) {
                        return $this->buildRoute($this->progreso->getModulo(), $this->progreso->getPregunta(), $prevDiapositiva);
                    }
                } else {
                    //si esta repitiendo una pregunta que fallo con diapositivas, vuelve a las diapositivas de la pregunta
                    if($this->evaluador->isPreguntaRepeticion() && $this->progreso->getPregunta()->hasDiapositivas() && !$this->evaluador->evaluarRespuesta()) {
                        return $this->buildRoute($this->progreso->getModulo(), $this->progreso->getPregunta(), $this->progreso->getPregunta()->getUltimaDiapositiva());
                    } else {
                        $pregunta = $this->getPreguntasContainer()->getPrevPregunta($this->progreso->getPregunta());
                        if ($pregunta) {
                            return $this->buildRoute($this->progreso->getModulo(), $pregunta);
                        }
                        return $this->buildRoute($this->progreso->getModulo(), $this->progreso->getModulo()->getUltimaDiapositiva());
                    }
                }
            }
        }
        return false;
    }

    public function isLastRoute()
    {
        return $this->progreso->getEvaluacion()->isLastModulo($this->progreso->getModulo());
    }

    public function getExitRoute()
    {
        return $this->router->generate('evaluacion_culminar', [
            'evaluacionSlug' => $this->progreso->getEvaluacion()->getSlug(),
            'progresoId' => $this->progreso->getId()
        ]);
    }

    /**
     * @return bool
     */
    public function esPregunta()
    {
        return $this->progreso->getDiapositiva() || $this->progreso->getPreguntaDiapositiva() ? false : true;
    }

    /**
     * @return Evaluacion
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }

    /**
     * @return Modulo
     */
    public function getModulo()
    {
        return $this->progreso->getModulo();
    }

    /**
     * @return Diapositiva|null
     */
    public function getDiapositiva()
    {
        return $this->progreso->getDiapositiva();
    }

    /**
     * @return Pregunta|null
     */
    public function getPregunta()
    {
        return $this->progreso->getPregunta();
    }

    /**
     * @return Diapositiva|null
     */
    public function getPreguntaDiapositiva(): ?Diapositiva
    {
        return $this->progreso->getPreguntaDiapositiva();
    }

    /**
     * @return Evaluador
     */
    public function getEvaluador(): Evaluador
    {
        return $this->evaluador;
    }

    /**
     * @return Progreso
     */
    public function getProgreso()
    {
        return $this->progreso;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Chequea acceso para evitar que se salte diapositivas o preguntas. Si nota inconsistencia modifica parametros
     * @param Modulo $modulo
     * @param Diapositiva|Pregunta $diapositivaOPregunta
     * @param Diapositiva|null $preguntaDiapositiva
     */
    private function checkAccess(Modulo &$modulo, &$diapositivaOPregunta, ?Diapositiva &$preguntaDiapositiva = null)
    {
        if($this->progreso->getId() && $this->buildRoute($modulo, $diapositivaOPregunta) !== $this->getNextRoute()) {
            list($curModulo, $curDiapositivaOPregunta, $curPreguntaDiapositiva) = $this->buildCurentRoute();
            $useCurrents = $modulo->getIndice() > $curModulo->getIndice();
            if(!$useCurrents) {
                if($diapositivaOPregunta instanceof Diapositiva && $curDiapositivaOPregunta instanceof  Diapositiva) {
                    $useCurrents = $diapositivaOPregunta->getIndice() > $curDiapositivaOPregunta->getIndice();
                } else if($diapositivaOPregunta instanceof Pregunta && $curDiapositivaOPregunta instanceof Pregunta) {
                    $useCurrents = $diapositivaOPregunta->getIndice() > $curDiapositivaOPregunta->getIndice()
                        || $diapositivaOPregunta->getModulo()->getIndice() > $curDiapositivaOPregunta->getModulo()->getIndice();
                }
                else {
                    if($diapositivaOPregunta instanceof Diapositiva) {
                        $useCurrents = $diapositivaOPregunta->getIndice() > $curModulo->getDiapositivas()->last()->getIndice();
                    } else {
                        $useCurrents = $diapositivaOPregunta->getModulo()->getIndice() > $curModulo->getIndice();
                    }
                }
            }
            if($useCurrents) {
                $modulo = $curModulo;
                $diapositivaOPregunta = $curDiapositivaOPregunta;
                $preguntaDiapositiva = $curPreguntaDiapositiva;
            }
        }
        //si no tiene progreso(esta empezando), obligatoriamente debe ser la primera diapositiva
        else if(!$this->progreso->getId()) {
            $modulo = $this->getModulo();
            if(!($diapositivaOPregunta instanceof Diapositiva) || $diapositivaOPregunta->getId() === $this->getDiapositiva()->getId()) {
                $diapositivaOPregunta = $this->getDiapositiva();
            }
        }
    }



    /**
     * @param Modulo $modulo
     * @param Diapositiva|Pregunta $diapositivaOPregunta
     * @param Diapositiva|null $preguntaDiapositiva
     * @return string
     */
    private function buildRoute(Modulo $modulo, $diapositivaOPregunta, ?Diapositiva $preguntaDiapositiva = null)
    {
        $evaluacion = $this->progreso->getEvaluacion();
        $routeParams = ['evaluacionSlug' => $evaluacion->getSlug(), 'moduloSlug' => $modulo->getSlug()];

        if($diapositivaOPregunta instanceof Pregunta) {
            $name = 'evaluacion_pregunta';
            $routeParams += ['preguntaId' => $diapositivaOPregunta->getId()];
            if($preguntaDiapositiva) {
                $name = 'evaluacion_pregunta_diapositiva';
                $routeParams += ['preguntaDiapositivaSlug' => $preguntaDiapositiva->getSlug()];
            }
        } else {
            $name = 'evaluacion_diapositiva';
            $routeParams += ['diapositivaSlug' => $diapositivaOPregunta->getSlug()];
        }
        return $this->router->generate($name, $routeParams);
    }

    /**
     * @return Modulo|ModuloRepeticionDecorator|null
     * @throws Exception
     */
    private function getPreguntasContainer()
    {
        return $this->evaluador && $this->evaluador->isModuloRepeticion() ? $this->evaluador->getModuloRepeticion() : $this->progreso->getModulo();
    }


    private function flushProgreso(?Modulo $modulo = null, ?Diapositiva $diapositiva = null,
                                   ?Pregunta $pregunta = null, ?Diapositiva $preguntaDiapositiva = null)
    {
        $this->progreso
            ->setModulo($modulo)
            ->setDiapositiva($diapositiva)
            ->setPregunta($pregunta)
            ->setPreguntaDiapositiva($preguntaDiapositiva);
        if($this->em) {
            if (!$this->progreso->getId()) {
                $this->em->persist($this->progreso);
            }
            $this->em->flush();
        }
    }

    private function buildNextRoute()
    {
        $modulo = $this->progreso->getModulo();
        $diapositivaOPregunta = null;
        $preguntaDiapositiva = null;

        if ($this->progreso->getDiapositiva()) {
            $diapositiva = $this->progreso->getModulo()->getNextDiapositiva($this->progreso->getDiapositiva());
            if($diapositiva) {
                $diapositivaOPregunta = $diapositiva;
            } else {
                if($this->progreso->moduloTienePreguntas()) {
                    $diapositivaOPregunta = $this->getPreguntasContainer()->getPrimeraPregunta();
                } else {
                    $modulo = $this->progreso->getNextModulo();
                    $diapositivaOPregunta = $modulo->getDiapositivas()->first();
                }
            }
        } else {
            if($this->progreso->getPreguntaDiapositiva()) {
                $diapositivaOPregunta = $this->progreso->getPregunta();
                $preguntaDiapositiva = $this->progreso->getPregunta()->getNextDiapositiva($this->progreso->getPreguntaDiapositiva());
            } else {
                // si fallo la respuesta y pregunta tiene diapositivas
                if($this->evaluador && $preguntaDiapositiva = $this->evaluador->getPreguntaDiapositiva($this->progreso->getPregunta())) {
                    $diapositivaOPregunta = $this->progreso->getPregunta();
                } else {
                    $diapositivaOPregunta = $this->getPreguntasContainer()->getNextPregunta($this->progreso->getPregunta());
                    if (!$diapositivaOPregunta) {
                        // modulo habilitado con repeticion en fallo, validamos respuestas ok
                        if($this->evaluador && $this->progreso->getModulo()->isRepetirEnFallo() && !$this->evaluador->evaluarModulo()) {
                            $diapositivaOPregunta = $this->progreso->getModulo()->getFirstDiapositiva();
                        } else {
                            $modulo = $this->progreso->getNextModulo();
                            $diapositivaOPregunta = $modulo->getDiapositivas()->first();
                        }
                    }
                }
            }
        }

        return [$modulo, $diapositivaOPregunta, $preguntaDiapositiva];
    }

    private function buildCurentRoute()
    {
        $modulo = $this->progreso->getModulo();
        $diapositivaOPregunta = $this->progreso->getDiapositiva();
        $preguntaDiapositiva = $this->progreso->getPreguntaDiapositiva();
        if(!$diapositivaOPregunta) {
            $diapositivaOPregunta = $this->progreso->getPregunta();
        }
        return [$modulo, $diapositivaOPregunta, $preguntaDiapositiva];
    }
}