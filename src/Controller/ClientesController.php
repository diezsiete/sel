<?php


namespace App\Controller;


use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\Clientes\ListadoNominaResumenDataTableType;
use App\DataTable\Type\Clientes\LiquidacionNominaResumenDataTableType;
use App\DataTable\Type\Clientes\TrabajadoresActivosDataTableType;
use App\Entity\Main\Empleado;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;
use App\Entity\Novasoft\Report\TrabajadorActivo;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaRepository;
use App\Service\Novasoft\Report\ReportFactory;
use App\Service\PortalClientes\PortalClientesService;
use DateTime;
use League\Flysystem\FilesystemInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClientesController extends BaseController
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
     * @Route("/sel/clientes/listado-nomina", name="clientes_listado_nomina")
     */
    public function listadoNomina(DataTableFactory $dataTableFactory, Request $request)
    {
        $datatableOptions = [];
        if($convenio = $this->portalClientesService->getRepresentanteConvenio()) {
            $datatableOptions['convenio'] = $convenio;
        }

        $datatable = $dataTableFactory
            ->createFromType(ListadoNominaResumenDataTableType::class, $datatableOptions, ['searching' => true])
            ->handleRequest($request);

        if($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('/clientes/listado-nomina.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/sel/clientes/listado-nomina/{id}", name="clientes_listado_nomina_detalle")
     */
    public function lisadoNominaDetalle(ListadoNomina $listadoNomina)
    {
        return $this->render('/clientes/listado-nomina-detalle.html.twig', [
            'listadoNomina' => $listadoNomina
        ]);
    }

    /**
     * @Route("/sel/clientes/listado-nomina/{id}/pdf", name="clientes_listado_nomina_detalle_pdf")
     */
    public function listadoNominaDetallePdf(ListadoNomina $listadoNomina, ReportFactory $reportFactory)
    {
        return $this->renderStream(function () use ($reportFactory, $listadoNomina) {
            return $reportFactory->clientes()->listadoNomina($listadoNomina)->streamPdf();
        });
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
    public function liquidacionNominaDetalle(LiquidacionNominaResumen $liquidacionNominaResumen)
    {
        return $this->render('/clientes/liquidacion-nomina-detalle.html.twig', [
            'liquidacionNominaResumen' => $liquidacionNominaResumen
        ]);
    }

    /**
     * @Route("/sel/clientes/liquidacion-nomina/{id}/pdf", name="clientes_liquidacion_nomina_detalle_pdf")
     */
    public function liquidacionNominaDetallePdf(LiquidacionNominaResumen $liqNominaResumen,
                                                ReportFactory $reportFactory, FilesystemInterface $novasoftReportFilesystem)
    {
        return $this->renderStream(function () use ($liqNominaResumen, $reportFactory, $novasoftReportFilesystem) {
            $report = $reportFactory->liquidacionNomina(
                $liqNominaResumen->getConvenio(), $liqNominaResumen->getFechaInicial(), $liqNominaResumen->getFechaFinal()
            );
            return $novasoftReportFilesystem->readStream($report->getFileNamePdf());
        });
    }
}