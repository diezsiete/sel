<?php


namespace App\Service\Napi\Report\Report;


use App\Entity\Main\Empleado;
use App\Entity\Napi\Report\ServicioEmpleados\Nomina;
use App\Service\Napi\Report\SsrsReport;

class NominaReport extends SsrsReport
{
    protected function callOperation(Empleado $empleado)
    {
        return $this->client->collectionOperations(Nomina::class)->get(
            $empleado->getCodEmp() ?? $empleado->getIdentificacion(), '2017-02-01', (new \DateTime())->format('Y-m-t')
        );
    }
}