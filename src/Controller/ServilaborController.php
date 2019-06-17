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
        return $this->render('servilabor/index.html.twig', [
            'controller_name' => 'ServilaborController',
        ]);
    }
}
