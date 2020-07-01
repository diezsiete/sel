<?php


namespace App\Service\Napi\Report\Report;


use App\Entity\Main\Empleado;
use App\Entity\Napi\Report\ServicioEmpleados\LiquidacionContrato;
use App\Service\Napi\Report\SsrsReport;
use DateTime;

class LiquidacionContratoReport extends SsrsReport
{
    protected function callOperation(Empleado $empleado)
    {
        return $this->client->collectionOperations(LiquidacionContrato::class)->get(
            $empleado->getCodEmp() ?? $empleado->getIdentificacion(), '2017-02-01', (new DateTime())->format('Y-m-t')
        );
    }
}