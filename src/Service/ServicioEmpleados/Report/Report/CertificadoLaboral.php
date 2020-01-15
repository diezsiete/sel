<?php


namespace App\Service\ServicioEmpleados\Report\Report;


use App\Repository\ServicioEmpleados\CertificadoLaboralRepository as SeCertificadoLaboralRepository;

class CertificadoLaboral
{
    private $identificacion;
    /**
     * @var SeCertificadoLaboralRepository
     */
    private $seCertificadoLaboralRepo;

    public function __construct(SeCertificadoLaboralRepository $seCertificadoLaboralRepo)
    {
        $this->seCertificadoLaboralRepo = $seCertificadoLaboralRepo;
    }

    /**
     * @param mixed $identificacion
     * @return CertificadoLaboral
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    public function renderMap()
    {
        return $this->seCertificadoLaboralRepo->findByIdentificacion($this->identificacion);
    }
}