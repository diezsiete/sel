<?php

namespace App\Controller;

use App\Entity\Evaluacion\Respuesta\Respuesta;
use App\Form\EvaluacionRespuestaFormType;
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
     *      "preguntaId": "\d+"
     * })
     */
    public function pregunta(Navegador $navegador, Request $request)
    {
        $respuesta = $navegador->getEvaluador()->buildRespuesta();
        $form = $this->createForm(EvaluacionRespuestaFormType::class, $respuesta);

        $pregunta = $navegador->getPregunta();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Respuesta $respuesta */
            $respuesta = $form->getData();
            $navegador->getEvaluador()->updateProgreso($respuesta);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($navegador->getNextRoute());
        }

        $template = $pregunta->getWidgetAsKebabCase();
        $view = "evaluacion/widget/$template.html.twig";
        return $this->render($view, [
            'evaluacion' => $navegador->getEvaluacion(),
            'navegador' => $navegador,
            'pregunta' => $pregunta,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{preguntaId}/{preguntaDiapositivaSlug}", name="evaluacion_pregunta_diapositiva", requirements={
     *      "preguntaId": "\d+"
     * })
     */
    public function preguntaDiapositiva(Navegador $navegador)
    {
        $view = "evaluacion/{$navegador->getEvaluacion()->getSlug()}/{$navegador->getPreguntaDiapositiva()->getSlug()}.html.twig";
        return $this->render($view, [
            'evaluacion' => $navegador->getEvaluacion(),
            'navegador' => $navegador
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
