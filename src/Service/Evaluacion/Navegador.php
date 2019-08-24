<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Usuario;
use App\Repository\Evaluacion\ProgresoRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    public function __construct(ProgresoRepository $progresoRepository, Security $security, RouterInterface $router)
    {
        $this->progresoRepository = $progresoRepository;
        $this->router = $router;
        $this->user = $security->getUser();
    }

    public function getCurrentRoute(Evaluacion $evaluacion)
    {
        $progreso = $this->getProgreso($evaluacion);
        $routeParams = [
            'evaluacionSlug' => $progreso->getEvaluacion()->getSlug(),
            'moduloSlug' => $progreso->getModulo()->getSlug()
        ];
        if($progreso->getPregunta()) {
            return $this->router->generate('evaluacion_pregunta', $routeParams + [
                'preguntaId' => $progreso->getPregunta()->getId()
            ]);
        } else {
            return $this->router->generate('evaluacion_diapositiva', $routeParams + [
                'diapositivaSlug' => $progreso->getDiapositiva()->getSlug()
            ]);
        }
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
     * @param Evaluacion $evaluacion
     * @return Progreso
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function getProgreso(Evaluacion $evaluacion)
    {
        if(!$this->progreso) {
            $this->progreso = $this->progresoRepository->findByUsuarioElseNew($this->user, $evaluacion);
        }
        return $this->progreso;
    }
}