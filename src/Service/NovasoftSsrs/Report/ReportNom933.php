<?php
/**
 * Reporte listado de empleados
 */

namespace App\Service\NovasoftSsrs\Report;

use App\Entity\Empleado;
use App\Service\NovasoftSsrs\Mapper\MapperNom933;

class ReportNom933 extends Report
{
    protected function getReportPath(): string
    {
        return "/ReportesWeb/NOM/NOM933";
    }

    protected function getMapperClass(): ?string
    {
        return MapperNom933::class;
    }

    /**
     * Identificación del empleado
     * @var string
     */
    protected $parameter_cod_emp = "%%";


    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_cod_emp = $identificacion . '%';
    }

    /**
     * @return Empleado[]
     */
    public function renderMap()
    {
        $csv = $this->renderCSV();
        return $this->reportFormatter->mapCsv($csv, $this->getMapperInstance());
    }
}