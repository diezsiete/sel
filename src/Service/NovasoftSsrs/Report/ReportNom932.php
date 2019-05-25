<?php
/**
 * Reporte datos certificado laboral
 */

namespace App\Service\NovasoftSsrs\Report;



use App\Service\NovasoftSsrs\Entity\ReporteCertificadoLaboral;
use App\Service\NovasoftSsrs\Mapper\MapperNom932;

class ReportNom932 extends Report
{
    protected function getReportPath(): string
    {
        return "/ReportesWeb/NOM/NOM932";
    }

    protected function getMapperClass(): ?string
    {
        return MapperNom932::class;
    }

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
     * @return ReporteCertificadoLaboral[]
     * @throws \SSRS\SSRSReportException
     */
    public function renderMap()
    {
        $csvAssociative = $this->reportFormatter->csvColsSplittedToAssociative($this->renderCSV());
        return $this->reportFormatter->mapCsv($csvAssociative, new $this->mapperClass());
    }

    /**
     * @return ReporteCertificadoLaboral|null
     * @throws \SSRS\SSRSReportException
     */
    public function renderCertificado()
    {
        $certificadoData = $this->renderMap();
        return $certificadoData ? $certificadoData[0] : null;
    }
}