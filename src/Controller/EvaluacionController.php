<?php

namespace App\Controller;

use App\DataTable\Type\EvaluacionProgresoDataTableType;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use App\Form\EvaluacionRespuestaFormType;
use App\Service\Evaluacion\Mensaje;
use App\Service\Evaluacion\Navegador;
use App\Service\Pdf\EvaluacionCertificado;
use DateTime;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class EvaluacionController extends BaseController
{

    /**
     * @Route("/evaluacion/presentar/evaluacion-de-induccion")
     */
    public function legacyPresentar()
    {
        return $this->redirectToRoute('evaluacion_menu_redirect', ['evaluacionSlug' => 'induccion']);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/menu-redirect", name="evaluacion_menu_redirect")
     */
    public function menuRedirect(Navegador $navegador)
    {
        if(!$navegador->getProgreso()->getId() || !$navegador->getProgreso()->getCulminacion()) {
            return $this->redirect($navegador->getCurrentRoute());
        } else {
            return $this->redirectToRoute('evaluacion_resultados');
        }
    }

    /**
     * @Route("/evaluacion/resultados", name="evaluacion_resultados")
     */
    public function resultados(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory
            ->createFromType(EvaluacionProgresoDataTableType::class, ['usuario' => $this->getUser()], ['searching' => true])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('evaluacion/resultados.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}", name="evaluacion")
     */
    public function presentar(Navegador $navegador)
    {
        if($navegador->getProgreso()->getCulminacion()) {
            return $this->redirectToRoute('evaluacion_resultados');
        }
        $currentRoute = $navegador->getCurrentRoute();
        return $this->redirect($currentRoute);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/culminar/{progresoId}", name="evaluacion_culminar")
     * @Entity("progreso", expr="repository.findByEvaluacionSlug(progresoId, evaluacionSlug)")
     */
    public function culminar(Progreso $progreso)
    {
        if($progreso->getPorcentajeCompletitud() < 100) {
            throw $this->createAccessDeniedException("No tiene acceso");
        } else {
            if(!$progreso->getCulminacion()) {
                $progreso->setCulminacion(new DateTime());
                $this->getDoctrine()->getManager()->flush();
            }
        }
        return $this->render("evaluacion/culminar.html.twig", [
            'evaluacion' => $progreso->getEvaluacion(),
            'progreso' => $progreso
        ]);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/certificado/{progresoId}", name="evaluacion_certificado")
     * @Entity("progreso", expr="repository.findByEvaluacionSlug(progresoId, evaluacionSlug)")
     */
    public function certificado(Progreso $progreso, EvaluacionCertificado $pdf)
    {
        if(!$progreso->getPorcentajeCompletitud()) {
            throw $this->createAccessDeniedException("Evaluación no culminada");
        }
        return $this->renderPdf($pdf->render($progreso));
    }


    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{preguntaId}", name="evaluacion_pregunta", requirements={
     *      "preguntaId": "\d+"
     * })
     */
    public function pregunta(Navegador $navegador, Request $request, Mensaje $mensaje)
    {
        if(!$navegador->getPregunta()) {
            return $this->redirect($navegador->getCurrentRoute());
        }
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

        if($mensaje->hasFlashMessage()) {
            $this->addFlash($mensaje->flashMessageType(), $mensaje->flashMessage());
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
    public function preguntaDiapositiva(Navegador $navegador, Mensaje $mensaje)
    {
        if($mensaje->hasFlashMessage()) {
            $this->addFlash($mensaje->flashMessageType(), $mensaje->flashMessage());
        }
        $view = "evaluacion/{$navegador->getEvaluacion()->getSlug()}/{$navegador->getPreguntaDiapositiva()->getSlug()}.html.twig";
        return $this->render($view, [
            'evaluacion' => $navegador->getEvaluacion(),
            'navegador' => $navegador
        ]);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{diapositivaSlug}", name="evaluacion_diapositiva")
     */
    public function diapositiva(Navegador $navegador, Mensaje $mensaje)
    {
        if(!$navegador->getDiapositiva()) {
            return $this->redirect($navegador->getCurrentRoute());
        }
        if($mensaje->hasFlashMessage()) {
            $this->addFlash($mensaje->flashMessageType(), $mensaje->flashMessage());
        }
        $view = "evaluacion/{$navegador->getEvaluacion()->getSlug()}/{$navegador->getDiapositiva()->getSlug()}.html.twig";
        return $this->render($view, [
            'evaluacion' => $navegador->getEvaluacion(),
            'navegador' => $navegador
        ]);
    }
}
