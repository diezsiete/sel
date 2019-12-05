<?php

namespace App\Controller;

use App\Service\Novasoft\Report\ReportFactory;
use App\Service\ServicioEmpleados\DataTable;
use App\Service\ServicioEmpleados\Import;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    /**
     * @Route("/sel", name="sel_panel")
     */
    public function panel(DataTable $dataTable, ReportFactory $reportFactory)
    {
        $datatables = [];
        $comprobantes = [];
        if($this->isGranted(['ROLE_EMPLEADO'], $this->getUser())) {
            /*$tableComprobantes = $dataTable->comprobantes(['dom' => 'l', 'pageLength' => 2]);
            if($tableComprobantes->isCallback()) {
                $import->nomina($this->getUser());
                return $tableComprobantes->getResponse();
            }*/
            $fecha = new DateTime();

            $comprobantes = $reportFactory->getReporteNomina(
                $this->getUser()->getIdentificacion(), (new DateTime())->sub(new DateInterval('P2M'))
            )->renderMap();

            $tableAportes = $dataTable->certificadosAportes(['dom' => 'l', 'pageLength' => 3]);
            if($tableAportes->isCallback()) {
                return $tableAportes->getResponse();
            }
            $datatables = [
                //'comprobantes' => $tableComprobantes,
                'aportes' => $tableAportes
            ];
        }

        return $this->render('panel/panel.html.twig', [
            'datatables' => $datatables,
            'comprobantes' => $comprobantes
        ]);
    }
}
