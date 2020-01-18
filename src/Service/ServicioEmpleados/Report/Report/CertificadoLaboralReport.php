<?php


namespace App\Service\ServicioEmpleados\Report\Report;


use App\Entity\ServicioEmpleados\CertificadoLaboral;
use App\Repository\ServicioEmpleados\CertificadoLaboralRepository as SeCertificadoLaboralRepository;

class CertificadoLaboralReport
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
     * @return CertificadoLaboralReport
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    /**
     * @return CertificadoLaboral[]
     */
    public function renderMap()
    {
        return $this->seCertificadoLaboralRepo->findByIdentificacion($this->identificacion);
    }

    /**
     * @return CertificadoLaboral|null
     */
    public function findLast()
    {
        return $this->seCertificadoLaboralRepo->findLastByIdentificacion($this->identificacion);
    }
}