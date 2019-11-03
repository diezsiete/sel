<?php

namespace App\Controller;

use App\Service\ServicioEmpleados\DataTable;
use App\Service\ServicioEmpleados\Import;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    /**
     * @Route("/sel", name="sel_panel")
     */
    public function panel(DataTable $dataTable, Import $import)
    {
        $datatables = [];
        if($this->isGranted(['ROLE_EMPLEADO'], $this->getUser())) {
            $tableComprobantes = $dataTable->comprobantes(['dom' => 'l', 'pageLength' => 2]);
            if($tableComprobantes->isCallback()) {
                $import->nomina($this->getUser());
                return $tableComprobantes->getResponse();
            }
            $tableAportes = $dataTable->certificadosAportes(['dom' => 'l', 'pageLength' => 3]);
            if($tableAportes->isCallback()) {
                return $tableAportes->getResponse();
            }
            $datatables = [
                'comprobantes' => $tableComprobantes,
                'aportes' => $tableAportes
            ];
        }

        return $this->render('panel/panel.html.twig', [
            'datatables' => $datatables
        ]);
    }
}
