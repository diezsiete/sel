<?php

namespace App\Controller;

use App\Service\ServicioEmpleados\DataTableBuilder;
use App\Service\ServicioEmpleados\Report\ReportFactory;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends BaseController
{
    /**
     * @Route("/sel", name="sel_panel")
     */
    public function panel(DataTableBuilder $dataTable, ReportFactory $reportFactory)
    {
        if($this->isGranted(['ROLE_EMPLEADO'], $this->getUser())) {
            return $this->panelEmpleado($dataTable, $reportFactory);
        }

        return $this->render('panel/main.html.twig');
    }

    public function panelEmpleado(DataTableBuilder $dataTable, ReportFactory $reportFactory)
    {
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


}
