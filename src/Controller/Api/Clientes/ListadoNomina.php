<?php


namespace App\Controller\Api\Clientes;


use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class ListadoNomina extends BaseController
{
    /**
     * @Route("/apx/listado-nomina/{convenio}", methods="GET", name="sel_api_listado_nomina_fechas", options={"expose" = true})
     */
    public function fechas()
    {
        
    }
    /**
     * @Route("/apx/listado-nomina/{convenio}/{fecha}/conceptos", methods="GET", name="sel_api_listado_nomina_conceptos", options={"expose" = true})
     */
    public function conceptos()
    {
    }

    /**
     * @Route("/apx/listado-nomina/{convenio}/{fecha}/resumen", methods="GET", name="sel_api_listado_nomina_resumen", options={"expose" = true})
     */
    public function resumen()
    {
    }

    /**
     * @Route("/apx/listado-nomina/{convenio}/{fecha}/{empleado}", methods="GET", name="sel_api_listado_nomina_detalle", options={"expose" = true})
     */
    public function detalle()
    {
    }

    /**
     * @Route("/apx/listado-nomina/{convenio}/resumen/{empleado}", methods="GET", name="sel_api_listado_nomina_resumen_empleado", options={"expose" = true})
     */
    public function resumenEmpleado()
    {
    }
}