<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Usuario;
use App\Repository\Evaluacion\ProgresoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use PhpParser\Node\Expr\AssignOp\Mod;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class Navegador
{
    /**
     * @var ProgresoRepository
     */
    private $progresoRepository;

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
     * @var UserInterface|null
     */
    private $user;
    /**
     * @var Evaluacion
     */
    private $evaluacion;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ProgresoRepository $progresoRepository, Evaluacion $evaluacion,
                                Security $security, RouterInterface $router, EntityManagerInterface $em)
    {
        $this->progresoRepository = $progresoRepository;
        $this->router = $router;
        $this->user = $security->getUser();
        $this->evaluacion = $evaluacion;
        $this->em = $em;
    }


    public function setRouteDiapositiva(Modulo $modulo, Diapositiva $diapositiva)
    {
        $progreso = $this->getProgreso();
        if(!$progreso->getDiapositiva() || $progreso->getDiapositiva()->getId() !== $diapositiva->getId()) {
            if($this->hasAccessToDiapositiva($diapositiva)) {
                $progreso
                    ->setModulo($modulo)
                    ->setDiapositiva($diapositiva);
                $this->em->flush();
            }
        }
    }

    public function setRoutePregunta(Modulo $modulo, Pregunta $pregunta)
    {

        $progreso = $this->getProgreso();
        if(!$progreso->getPregunta() || $progreso->getPregunta()->getId() !== $pregunta->getId() || $progreso->getPreguntaDiapositiva()) {
            if($this->hasAccessToPregunta($pregunta)) {
                $progreso
                    ->setModulo($modulo)
                    ->setPregunta($pregunta);
                $this->em->flush();
            }
        }
    }

    public function setRoutePreguntaDiapositiva(Modulo $modulo, Pregunta $pregunta, Diapositiva $preguntaDiapositiva)
    {
        $progreso = $this->getProgreso();
        if(!$progreso->getPreguntaDiapositiva() || $progreso->getPreguntaDiapositiva()->getId() !== $preguntaDiapositiva->getId()) {
            if($this->hasAccessToPregunta($pregunta)) {
                $progreso
                    ->setModulo($modulo)
                    ->setPregunta($pregunta)
                    ->setPreguntaDiapositiva($preguntaDiapositiva);

                $this->em->flush();
            }
        }
    }

    public function getCurrentRoute()
    {
        $progreso = $this->getProgreso();
        $diapositivaOPregunta = $this->progreso->getDiapositiva();
        if(!$diapositivaOPregunta) {
            $diapositivaOPregunta = $this->progreso->getPregunta() ?? $this->progreso->getPreguntaDiapositiva();
        }
        return $this->buildRoute($progreso->getModulo(), $diapositivaOPregunta);
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
            if ($this->progreso->getDiapositiva()) {
                $diapositiva = $this->progreso->getModulo()->getNextDiapositiva($this->progreso->getDiapositiva());
                if($diapositiva) {
                    return $this->buildRoute($this->progreso->getModulo(), $diapositiva);
                } else {
                    if($this->progreso->moduloTienePreguntas()) {
                        return $this->buildRoute($this->progreso->getModulo(), $this->progreso->getModulo()->getPreguntas()->first());
                    } else {
                        $nextModulo = $this->progreso->getNextModulo();
                        return $this->buildRoute($nextModulo, $nextModulo->getDiapositivas()->first());
                    }
                }
            } else {
                if($this->progreso->getPreguntaDiapositiva()) {
                    return $this->buildRoute(
                        $this->progreso->getModulo(),
                        $this->progreso->getPregunta(),
                        $this->progreso->getPregunta()->getNextDiapositiva($this->progreso->getPreguntaDiapositiva()));
                } else {
                    if($preguntaDiapositiva = $this->evaluador->getPreguntaDiapositiva($this->progreso->getPregunta())) {
                        return $this->buildRoute($this->progreso->getModulo(), $this->progreso->getPregunta(), $preguntaDiapositiva);
                    } else {
                        $sigPregunta = $this->progreso->getModulo()->getNextPregunta($this->progreso->getPregunta());
                        if ($sigPregunta) {
                            return $this->buildRoute($this->progreso->getModulo(), $sigPregunta);
                        } else {
                            $nextModulo = $this->progreso->getNextModulo();
                            return $this->buildRoute($nextModulo, $nextModulo->getDiapositivas()->first());
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasPrevRoute()
    {
        if($this->progreso->getDiapositiva()) {
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
                        $pregunta = $this->progreso->getModulo()->getPrevPregunta($this->progreso->getPregunta());
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

    /**
     * @return bool
     */
    public function esPregunta()
    {
        return $this->progreso->getDiapositiva() || $this->progreso->getPreguntaDiapositiva() ? false : true;
    }

    /**
     * @param UserInterface $user
     * @return Navegador
     */
    public function setUser(UserInterface $user): Navegador
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Progreso
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function getProgreso()
    {
        if(!$this->progreso) {
            $this->progreso = $this->progresoRepository->findByUsuarioElseNew($this->user, $this->evaluacion);
            if(!$this->progreso->getId()) {
                $this->em->persist($this->progreso);
                $this->em->flush();
            }
            $this->evaluador = new Evaluador($this->progreso);
        }
        return $this->progreso;
    }

    private function hasAccessToDiapositiva(Diapositiva $diapositiva)
    {
        // TODO
        return true;
    }

    private function hasAccessToPregunta(Pregunta $pregunta)
    {
        // TODO
        return true;
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
     * @return Evaluacion
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }
    /**
     * @return Diapositiva
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getDiapositiva()
    {
        return $this->getProgreso()->getDiapositiva();
    }

    /**
     * @return Pregunta|null
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getPregunta()
    {
        return $this->getProgreso()->getPregunta();
    }

    /**
     * @return Evaluador
     */
    public function getEvaluador(): Evaluador
    {
        return $this->evaluador;
    }

    public function getPreguntaDiapositiva(): ?Diapositiva
    {
        return $this->getProgreso()->getPreguntaDiapositiva();
    }
}