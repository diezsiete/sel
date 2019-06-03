<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AspiranteController extends AbstractController
{
    /**
     * @Route("/aspirante/crear", name="aspirante_crear")
     */
    public function index()
    {
        return $this->render('aspirante/crear.html.twig');
    }
}
