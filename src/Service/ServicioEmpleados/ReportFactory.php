<?php


namespace App\Service\ServicioEmpleados;


class ReportFactory
{
    public function getReporteNomina($identificacion = null, $fechaInicio = null, $fechaFin = null, $ssrsdb = null)
    {
        $doctrine = $this->getDoctrine();
        $vinculacionRepo = $doctrine->getRepository(Vinculacion::class);
        $vinculacion = $vinculacionRepo->find('-1603072');
        dump($vinculacion);
    }
}