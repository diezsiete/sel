<?php

namespace App\Controller;

use App\Service\SelParameters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PtaController extends AbstractController
{
    /**
     * @Route("/", name="pta_index", host="%empresa.pta.host%")
     */
    public function index()
    {
        return $this->render('pta/index.html.twig', [
            'controller_name' => 'PtaController',
        ]);
    }

    /**
     * @Route("/nosotros", name="pta_nosotros", host="%empresa.pta.host%")
     * @return Response
     */
    public function nosotros()
    {
        return $this->render('pta/nosotros.html.twig');
    }

    /**
     * @Route("/servicios", name="pta_servicios", host="%empresa.pta.host%")
     */
    public function servicios()
    {
        return $this->render('pta/servicios.html.twig');
    }

    /**
     * @Route("/noticias", name="pta_noticias", host="%empresa.pta.host%")
     */
    public function noticias()
    {
        return $this->render('pta/noticias.html.twig');
    }



    /**
     * @Route("/contacto", name="pta_contacto", host="%empresa.pta.host%")
     */
    public function contacto()
    {
        return $this->render('pta/contacto.html.twig');
    }
}
