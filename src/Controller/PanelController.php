<?php

namespace App\Controller;

use App\Service\Component\LoadingOverlayComponent;
use App\Service\ServicioEmpleados\DataTableBuilder;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use App\Service\ServicioEmpleados\Report\ReportFactory;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sel", name="sel_panel")
 */
class PanelController extends BaseController implements ActionBasedOnRole
{
    /**
     * @var LoadingOverlayComponent
     */
    private $loadingOverlay;
    /**
     * @var ReportCacheHandler
     */
    private $reportCacheHandler;

    public function __construct(LoadingOverlayComponent $loadingOverlay, ReportCacheHandler $reportCacheHandler)
    {
        $this->loadingOverlay = $loadingOverlay;
        $this->reportCacheHandler = $reportCacheHandler;
    }


    public function __invoke()
    {
        return $this->render('panel/main.html.twig');
    }

    public function empleado(DataTableBuilder $dataTable, ReportFactory $reportFactory)
    {
        /*if(!$this->loadingOverlay->isEnabled() && $this->reportCacheHandler->hasCacheToRenew($this->getUser())) {
            $this->loadingOverlay->enable();
        }*/

        $tableNomina = $dataTable->nomina(['dom' => 't', 'pageLength' => 2]);
        if($tableNomina->isCallback()) {
            return $tableNomina->getResponse();
        }
        $tableAportes = $dataTable->certificadosAportes(['dom' => 't', 'pageLength' => 3]);
        if($tableAportes->isCallback()) {
            return $tableAportes->getResponse();
        }

        $certificadoLaboral = $reportFactory->certificadoLaboral($this->getUser()->getIdentificacion())->findLast();

        return $this->render('panel/empleado.html.twig', [
            'datatables' => [
                'comprobantes' => $tableNomina,
                'aportes' => $tableAportes
            ],
            'certificadoLaboral' => $certificadoLaboral
        ]);
    }

//    /**
//     * @Route("/sel/panel/empleado/update", name="sel_panel_empleado_update", options={"expose" = true})
//     */
//    public function panelEmpleadoUpdate()
//    {
//        $this->reportCacheHandler->handleAll($this->getUser());
//        return $this->json(['ok' => true]);
//    }



}
