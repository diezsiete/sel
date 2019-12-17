<?php


namespace App\Controller;


use App\DataTable\Type\PortalClientes\LiquidacionNominaResumenDataTableType;
use App\DataTable\Type\PortalClientes\TrabajadoresActivosDataTableType;
use App\Entity\Empleado;
use App\Service\PortalClientes\PortalClientesService;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PortalClientesController extends BaseController
{
    /**
     * @var PortalClientesService
     */
    private $portalClientesService;

    public function __construct(PortalClientesService $portalClientesService)
    {

        $this->portalClientesService = $portalClientesService;
    }

    /**
     * @Route("/sel/clientes/trabajadores-activos", name="clientes_trabajadores_activos")
     */
    public function trabajadoresActivos(DataTableFactory $dataTableFactory, Request $request)
    {
        $datatableOptions = [];
        if($convenio = $this->portalClientesService->getRepresentanteConvenio()) {
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

    /**
     * @Route("/sel/clientes/liquidaciones-nomina", name="clientes_liquidaciones_nomina")
     */
    public function liquidacionesNomina(DataTableFactory $dataTableFactory, Request $request)
    {
        $datatableOptions = [];
        if($convenio = $this->portalClientesService->getRepresentanteConvenio()) {
            $datatableOptions['convenio'] = $convenio;
        }

        $datatable = $dataTableFactory
            ->createFromType(LiquidacionNominaResumenDataTableType::class, $datatableOptions, ['searching' => true])
            ->handleRequest($request);

        if($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('/clientes/liquidaciones-nomina.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/sel/clientes/liquidacion-nomina/{id}", name="clientes_liquidacion_nomina_detalle")
     */
    public function liquidacionNominaDetalle()
    {

    }
}