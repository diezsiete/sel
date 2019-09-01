<?php

namespace App\Controller;

use App\Service\ServicioEmpleados\DataTable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/sel", name="app_main")
     */
    public function index(DataTable $dataTable)
    {
        $datatables = [];
        if($this->isGranted(['ROLE_EMPLEADO'], $this->getUser())) {
            $tableComprobantes = $dataTable->comprobantes(['dom' => 'l', 'pageLength' => 5]);
            if($tableComprobantes->isCallback()) {
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

        return $this->render('main/index.html.twig', [
            'datatables' => $datatables
        ]);
    }
}
