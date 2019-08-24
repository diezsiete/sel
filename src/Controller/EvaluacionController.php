<?php

namespace App\Controller;

use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Service\Evaluacion\Navegador;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class EvaluacionController extends AbstractController
{
    /**
     * @var Navegador
     */
    private $navegador;

    public function __construct(Navegador $navegador)
    {
        $this->navegador = $navegador;
    }

    /**
     * @Route("/evaluacion/{slug}", name="evaluacion")
     */
    public function presentar(Evaluacion $evaluacion)
    {
        return $this->redirect($this->navegador->getCurrentRoute($evaluacion));

        /*return $this->render('evaluacion/presentar.html.twig', [
            'evaluacion' => $evaluacion,
        ]);*/
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{diapositivaSlug}", name="evaluacion_diapositiva")
     * @ParamConverter("evaluacion", options={"mapping": {"evaluacionSlug":"slug"}})
     * @ParamConverter("modulo", options={"mapping": {"moduloSlug": "slug"}})
     * @ParamConverter("diapositiva", options={"mapping": {"diapositivaSlug": "slug"}})
     */
    public function diapositiva(Evaluacion $evaluacion, Modulo $modulo, Diapositiva $diapositiva)
    {
        return $this->render('evaluacion/diapositiva/' . $diapositiva->getSlug() . '.html.twig', [
            'evaluacion' => $evaluacion
        ]);
    }

    /**
     * @Route("/evaluacion/{evaluacionSlug}/{moduloSlug}/{preguntaId}", name="evaluacion_pregunta", requirements={
            "preguntaId": "\d+"
     * })
     * @ParamConverter("evaluacion", options={"mapping": {"evaluacionSlug":"slug"}})
     * @ParamConverter("modulo", options={"mapping": {"moduloSlug": "slug"}})
     * @ParamConverter("diapositiva", options={"mapping": {"preguntaId": "id"}})
     */
    public function pregunta(Evaluacion $evaluacion, Modulo $modulo, Diapositiva $diapositiva)
    {

    }
}
