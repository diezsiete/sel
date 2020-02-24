<?php


namespace App\Controller\Api\Clientes;


use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class ListadoNomina extends BaseController
{
    /**
     * @Route("/api/listado-nomina/{convenio}/{fecha}/conceptos", methods="GET", name="sel_api_listado_nomina_conceptos", options={"expose" = true})
     */
    public function conceptos()
    {
    }

    /**
     * @Route("/api/listado-nomina/{convenio}/{fecha}/resumen", methods="GET", name="sel_api_listado_nomina_resumen", options={"expose" = true})
     */
    public function resumen()
    {
    }
}