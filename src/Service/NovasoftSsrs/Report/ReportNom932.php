<?php
/**
 * Reporte datos certificado laboral
 */

namespace App\Service\NovasoftSsrs\Report;



use App\Service\NovasoftSsrs\Mapper\MapperNom932;

class ReportNom932 extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM932";
    /**
     * @var string
     */
    protected $mapperClass = MapperNom932::class;

    /**
     * Identificación del empleado
     * @var string
     */
    protected $parameter_cod_emp;


    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_cod_emp = $identificacion;
    }

    /**
     * @return mixed
     * @throws \SSRS\SSRSReportException
     */
    public function renderMap()
    {
        $csvAssociative = $this->reportFormatter->csvSplittedToAssociative($this->renderCSV());
        return $this->reportFormatter->mapCsv($csvAssociative, new $this->mapperClass());
    }
}