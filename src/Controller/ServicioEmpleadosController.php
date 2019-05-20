<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServicioEmpleadosController extends AbstractController
{
    /**
     * @Route("/comprobantes", name="app_comprobantes")
     */
    public function comprobantes()
    {
        return $this->render('main/index.html.twig');
    }
}
