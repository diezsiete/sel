<?php


namespace App\Service\ServicioEmpleados\Report\Report;


use App\Repository\ServicioEmpleados\CertificadoIngresosRepository;

class CertificadoIngresosReport
{
    private $identificacion;

    /**
     * @var CertificadoIngresosRepository
     */
    private $certificadoIngresosRepo;


    public function __construct(CertificadoIngresosRepository $certificadoIngresosRepo)
    {
        $this->certificadoIngresosRepo = $certificadoIngresosRepo;
    }

    /**
     * @param mixed $identificacion
     * @return $this
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    public function renderMap()
    {
        return $this->certificadoIngresosRepo->findByIdentificacion($this->identificacion);
    }
}