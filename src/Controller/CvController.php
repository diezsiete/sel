<?php

namespace App\Controller;

use App\Service\Cv\CvService;
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
     * @Route("/sel/cv/{any}", name="cv", defaults={"any"=null}, requirements={"any"=".*"})
     */
    public function cv(CvService $cvService)
    {
        return $this->render('cv/cv.html.twig', [
            'cvIri' => $cvService->getUserCvIri(),
        ]);
    }
}