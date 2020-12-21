<?php

namespace App\Controller;

use App\Service\DocumentosLaborales\DocumentosLaborales;
use App\Service\Evaluacion\EvaluacionService;
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
     * @var ReportCacheHandler
     */
    private $reportCacheHandler;
    /**
     * @var EvaluacionService
     */
    private $evalacionService;

    public function __construct(ReportCacheHandler $reportCacheHandler)
    {
        $this->reportCacheHandler = $reportCacheHandler;
    }


    public function __invoke()
    {
        return $this->render('panel/main.html.twig');
    }

    public function superadmin()
    {
        return $this->render('panel/main.html.twig');
    }

    public function empleado(
        DataTableBuilder $dataTable,
        ReportFactory $reportFactory,
        EvaluacionService $evaluacionService,
        DocumentosLaborales $documentosLaborales
    ) {
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

        $evaluacionLink = $this->generateUrl('evaluacion', ['evaluacionSlug' => 'induccion']);
        $evaluacionPorcentaje = 0;
        if($evaluacionProgreso = $evaluacionService->usuarioHasEvaluacion($this->getUser(), 'induccion')) {
            $evaluacionPorcentaje = $evaluacionProgreso->getPorcentajeCompletitud();
            if($evaluacionProgreso->getCulminacion()) {
                $evaluacionLink = $this->generateUrl('evaluacion_certificado', ['evaluacionSlug' => 'induccion', 'progresoId' => $evaluacionProgreso->getId()]);
            }
        }

        return $this->render('panel/empleado.html.twig', [
            'datatables' => [
                'comprobantes' => $tableNomina,
                'aportes' => $tableAportes
            ],
            'certificadoLaboral' => $certificadoLaboral,
            'evaluacionLink' => $evaluacionLink,
            'evaluacionPorcentaje' => $evaluacionPorcentaje,
            'documentosLaborales' => $documentosLaborales->get(null, false)
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
