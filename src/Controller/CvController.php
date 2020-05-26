<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CvController extends AbstractController
{
    /**
     * @Route("/registro2", name="cv_registro")
     */
    public function registro()
    {
        return $this->render('cv/registro.html.twig');
    }

    /**
     * @Route("/sel/cv", name="cv")
     * @Route("/sel/cv/datos-basicos", name="cv_datos_basicos")
     * @Route("/sel/cv/estudios", name="cv_estudios")
     */
    public function cv()
    {
        return $this->render('cv/cv.html.twig');
    }
}