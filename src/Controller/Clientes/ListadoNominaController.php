<?php


namespace App\Controller\Clientes;


use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class ListadoNominaController extends BaseController
{
    /**
     * @Route("/sel/clientes/listado-nomina2", name="clientes_listado_nomina2")
     */
    public function listadoNomina()
    {
        return $this->render('/clientes/listado-nomina/listado.html.twig');
    }
}