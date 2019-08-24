<?php

namespace App\Controller;

use App\Entity\Evaluacion\Evaluacion;
use App\Repository\Evaluacion\ProgresoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class EvaluacionController extends AbstractController
{
    /**
     * @Route("/evaluacion/presentar/{slug}", name="evaluacion")
     */
    public function presentar(Evaluacion $evaluacion, ProgresoRepository $progresoRepository)
    {
        $progreso = $progresoRepository->findByUsuarioElseNew($this->getUser(), $evaluacion);
        dump($progreso->getDiapositiva());

        return $this->render('evaluacion/presentar.html.twig', [
            'evaluacion' => $evaluacion,
        ]);
    }
}
