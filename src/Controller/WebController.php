<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    /**
     * @Route("/politica-tratamiento-datos-personales", name="politica")
     */
    public function politicaTratamientoDatosPersonales()
    {
        return $this->render('web/politica-tratamiento-datos-personales.html.twig');
    }

    /**
     * @Route("/aviso-privacidad", name="aviso_privacidad")
     */
    public function avisoPrivacidad()
    {
        return $this->render('web/aviso-privacidad.html.twig');
    }

}