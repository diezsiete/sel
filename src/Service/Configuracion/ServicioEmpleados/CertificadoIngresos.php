<?php


namespace App\Service\Configuracion\ServicioEmpleados;


class CertificadoIngresos extends Report
{
    public function getAnos()
    {
        return $this->config['anos'];
    }
}