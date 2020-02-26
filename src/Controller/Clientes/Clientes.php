<?php


namespace App\Controller\Clientes;


use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class Clientes extends BaseController
{
    /**
     * @Route("/sel/clientes/{etc}", name="clientes", defaults={"etc":null}, requirements={"etc":".*"})
     */
    public function clientes()
    {
        return $this->render('/clientes/clientes.html.twig');
    }
}