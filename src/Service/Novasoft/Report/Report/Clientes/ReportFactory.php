<?php


namespace App\Service\Novasoft\Report\Report\Clientes;


use App\Entity\Main\Convenio;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina;
use DateTime;
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
     * @param Convenio|ListadoNomina $convenio
     * @param DateTimeInterface|null $fechaInicio
     * @param DateTimeInterface|null $fechaFin
     * @return ListadoNominaReport
     */
    public function listadoNomina($convenio, ?DateTimeInterface $fechaInicio = null, ?DateTimeInterface $fechaFin = null)
    {
        if($convenio instanceof ListadoNomina) {
            $listadNomina = $convenio;
            $convenio = $listadNomina->getConvenio();
            $fechaFin = $listadNomina->getFechaNomina();
            if($listadNomina->getFechaNomina()->format('d') == 15) {
                $fechaInicio = DateTime::createFromFormat('Y-m-d', $listadNomina->getFechaNomina()->format('Y-m') . "-01");
            } else {
                //TODO, si es final de mes puede que halla listado en 15 por lo que fecha inicial seria 16?
                $fechaInicio = DateTime::createFromFormat('Y-m-d', $listadNomina->getFechaNomina()->format('Y-m') . "-01");
            }
        }
        return $this->listadoNominaReport
            ->setFechaInicial($fechaInicio)
            ->setFechaFinal($fechaFin)
            ->setConvenio($convenio);
    }
}