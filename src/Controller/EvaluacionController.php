<?php

namespace App\Controller;

use App\Form\EvaluacionPreguntaFormType;
use App\Service\Evaluacion\Navegador;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;


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
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{preguntaId}", name="evaluacion_pregunta", requirements={
            "preguntaId": "\d+"
     * })
     */
    public function pregunta(Navegador $navegador, Request $request)
    {
        $pregunta = $navegador->getPregunta();
        $form = $this->createForm(EvaluacionPreguntaFormType::class, $pregunta);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // TODO guardar respuesta
            return $this->redirect($navegador->getNextRoute());
        }

        $template = str_replace("_", "-",
            (new CamelCaseToSnakeCaseNameConverter(null))->normalize(
                substr(strrchr(get_class($pregunta), "\\"), 1)
            ));

        $view = "evaluacion/widget/$template.html.twig";
        return $this->render($view, [
            'evaluacion' => $navegador->getEvaluacion(),
            'navegador' => $navegador,
            'pregunta' => $pregunta,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{diapositivaSlug}", name="evaluacion_diapositiva")
     */
    public function diapositiva(Navegador $navegador)
    {
        $view = "evaluacion/{$navegador->getEvaluacion()->getSlug()}/{$navegador->getDiapositiva()->getSlug()}.html.twig";
        return $this->render($view, [
            'evaluacion' => $navegador->getEvaluacion(),
            'navegador' => $navegador
        ]);
    }
}
