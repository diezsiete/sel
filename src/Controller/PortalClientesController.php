<?php


namespace App\Controller;


use App\DataTable\Type\PortalClientes\TrabajadoresActivosDataTableType;
use App\Entity\Empleado;
use App\Service\PortalClientes\PortalClientesService;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PortalClientesController extends BaseController
{

    /**
     * @Route("/sel/clientes/trabajadores-activos", name="clientes_trabajadores_activos")
     */
    public function trabajadoresActivos(DataTableFactory $dataTableFactory, Request $request, PortalClientesService $portalClientesService)
    {
        $datatableOptions = [];
        if($convenio = $portalClientesService->getRepresentanteConvenio()) {
            $datatableOptions['convenio'] = $convenio;
        }
        $datatable = $dataTableFactory
            ->createFromType(TrabajadoresActivosDataTableType::class, $datatableOptions, ['searching' => true])
            ->handleRequest($request);

        if($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('/clientes/trabajadores-activos.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/sel/clientes/empleado/{eid}", name="clientes_empleado")
     */
    public function empleado(Empleado $empleado)
    {

    }
}