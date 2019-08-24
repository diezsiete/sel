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
     * @var Modulo
     */
    private $modulo;
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
        if($progreso->getDiapositiva()->getId() !== $diapositiva->getId()) {
            if($this->hasAccessToDiapositiva($diapositiva)) {
                $progreso
                    ->setModulo($modulo)
                    ->setDiapositiva($diapositiva);
                // $this->em->flush();
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
        return $this->buildRoute($progreso->getEvaluacion(), $progreso->getModulo(), $diapositivaOPregunta);
    }

    public function getNextRoute()
    {
        if($this->hasNextRoute()) {
            if ($this->progreso->getDiapositiva()) {
                $diapositiva = $this->progreso->getModulo()->getNextDiapositiva($this->progreso->getDiapositiva());
                if($diapositiva) {
                    return $this->buildRoute($this->progreso->getEvaluacion(), $this->progreso->getModulo(), $diapositiva);
                } else {
                    // TODO
                }
            } else {
                // TODO
            }
        }
        return false;
    }

    public function getPrevRoute()
    {
        if($this->hasPrevRoute()) {
            if ($this->progreso->getDiapositiva()) {
                $diapositiva = $this->progreso->getModulo()->getPrevDiapositiva($this->progreso->getDiapositiva());
                if($diapositiva) {
                    return $this->buildRoute($this->progreso->getEvaluacion(), $this->progreso->getModulo(), $diapositiva);
                } else {
                    // TODO
                }
            } else {
                // TODO
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
            // TODO
            return false;
        }
    }

    /**
     * @return bool
     */
    public function hasNextRoute()
    {
        if($this->progreso->getDiapositiva()) {
            //TODO
            return true;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function esPregunta()
    {
        return $this->progreso->getDiapositiva() ? false : true;
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
        }
        return $this->progreso;
    }

    private function hasAccessToDiapositiva(Diapositiva $diapositiva)
    {
        // TODO
        return true;
    }

    private function buildRoute(Evaluacion $evaluacion, Modulo $modulo, $diapositivaOPregunta)
    {
        $routeParams = ['evaluacionSlug' => $evaluacion->getSlug(), 'moduloSlug' => $modulo->getSlug()];
        if($diapositivaOPregunta instanceof Pregunta) {
            return $this->router->generate('evaluacion_pregunta', $routeParams + [
                'preguntaId' => $diapositivaOPregunta->getId()
            ]);
        } else {
            return $this->router->generate('evaluacion_diapositiva', $routeParams + [
                'diapositivaSlug' => $diapositivaOPregunta->getSlug()
            ]);
        }
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

}