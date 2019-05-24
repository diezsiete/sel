<?php

namespace App\Controller;


use App\Service\NovasoftSsrs\Report\ReportNom204;
use App\Service\NovasoftSsrs\Report\ReportNomU1503;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ServicioEmpleadosController extends BaseController
{
    /**
     * @Route("/comprobantes", name="app_comprobantes")
     */
    public function comprobantes(ReportNom204 $reporte)
    {
        $reporte->setParameterCodigoEmpleado('53124855');
        $nominas = $reporte->renderMap();

        krsort($nominas);

        return $this->render('servicio_empleados/comprobantes.html.twig', [
            'nominas' => $nominas
        ]);
    }

    /**
     * @Route("/comprobante/{fecha}", name="app_comprobante")
     */
    public function comprobante(ReportNom204 $reporte, $fecha)
    {
        $fecha = \DateTime::createFromFormat('Y-m-d', $fecha);

        $reporte->setParameterCodigoEmpleado('53124855')
            ->setParameterFechaInicio($fecha)
            ->setParameterFechaFin($fecha);

        return $this->renderPdf($reporte->renderPdf());
    }
    
}
