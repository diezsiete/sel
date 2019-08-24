<?php

namespace App\Controller;

use App\Service\Evaluacion\Navegador;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class EvaluacionController extends AbstractController
{

    /**
     * @Route("/evaluacion/{evaluacionSlug}", name="evaluacion")
     */
    public function presentar(Navegador $navegador)
    {
        $currentRoute = $navegador->getCurrentRoute();
        return $this->redirect($currentRoute);

        /*return $this->render('evaluacion/presentar.html.twig', [
            'evaluacion' => $evaluacion,
        ]);*/
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{diapositivaSlug}", name="evaluacion_diapositiva")
     */
    public function diapositiva(Navegador $navegador)
    {
        return $this->render('evaluacion/diapositiva/' . $navegador->getDiapositiva()->getSlug() . '.html.twig', [
            'evaluacion' => $navegador->getEvaluacion(),
            'navegador' => $navegador
        ]);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{preguntaId}", name="evaluacion_pregunta", requirements={
            "preguntaId": "\d+"
     * })
     */
    public function pregunta(Navegador $navegador)
    {

    }
}
