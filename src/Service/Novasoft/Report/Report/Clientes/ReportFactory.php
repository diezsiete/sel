<?php


namespace App\Service\Novasoft\Report\Report\Clientes;


use App\Entity\Main\Convenio;
use DateTimeInterface;

class ReportFactory
{
    /**
     * @var ListadoNominaReport
     */
    private $listadoNominaReport;

    public function __construct(ListadoNominaReport $listadoNominaReport)
    {
        $this->listadoNominaReport = $listadoNominaReport;
    }

    /**
     * @return ListadoNominaReport
     */
    public function listadoNomina(Convenio $convenio, DateTimeInterface $fechaInicio, DateTimeInterface $fechaFin)
    {
        return $this->listadoNominaReport
            ->setFechaInicial($fechaInicio)
            ->setFechaFinal($fechaFin)
            ->setConvenio($convenio);
    }
}