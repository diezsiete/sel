<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VacanteController extends AbstractController
{
    /**
     * @Route("/vacante/crear", name="vacante_crear")
     */
    public function crear()
    {
        return $this->render('vacante/index.html.twig', [
            'controller_name' => 'VacanteController',
        ]);
    }
}
