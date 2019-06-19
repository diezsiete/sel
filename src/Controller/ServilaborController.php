<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServilaborController extends AbstractController
{
    /**
     * @Route("/", name="servilabor_index", host="%empresa.servilabor.host%")
     */
    public function index()
    {
        return $this->render('servilabor/index.html.twig');
    }

    /**
     * @Route("/nosotros", name="servilabor_nosotros", host="%empresa.servilabor.host%")
     */
    public function nosotros()
    {
        return $this->render('servilabor/nosotros.html.twig');
    }

    /**
     * @Route("/servicios", name="servilabor_servicios", host="%empresa.servilabor.host%")
     */
    public function servicios()
    {
        return $this->render('servilabor/servicios.html.twig');
    }
}
