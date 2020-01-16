<?php


namespace App\Service\Halcon\Report\Report;




use App\Repository\Halcon\CertificadoIngresosRepository;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class CertificadoIngresosReport extends Report
{
    private $identificacion;

    private $usuario;

    private $noContrat;

    private $ano;

    /**
     * @var CertificadoIngresosRepository
     */
    private $certificadoIngresosRepo;

    public function __construct(PdfHandler $pdfHandler, CertificadoIngresosRepository $certificadoIngresosRepo)
    {
        parent::__construct($pdfHandler);
        $this->certificadoIngresosRepo = $certificadoIngresosRepo;
    }

    /**
     * @param string $identificacion
     * @return $this
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    /**
     * @param string $usuario
     * @return CertificadoIngresosReport
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @param string $noContrat
     * @return CertificadoIngresosReport
     */
    public function setNoContrat($noContrat)
    {
        $this->noContrat = $noContrat;
        return $this;
    }

    /**
     * @param string $ano
     * @return CertificadoIngresosReport
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    public function renderMap()
    {
        $criteria = ['nitTercer' => $this->identificacion];
        if($this->usuario) {
            $criteria['usuario'] = $this->usuario;
        }
        if($this->noContrat) {
            $criteria['noContrat'] = $this->noContrat;
        }
        if($this->ano) {
            $criteria['ano'] = $this->ano;
        }

        return $this->certificadoIngresosRepo->findBy($criteria);
    }

    public function renderPdf()
    {
//        $certificado = $this->certificadoLaboralRepo->find($this->identificacion, $this->numeroContrato);
//        $tercero = $this->terceroRepository->find($this->identificacion);
//        return $this->certificadoLaboralPdf->build($certificado, $tercero)->Output("S");
    }


    public function getPdfFileName(): string
    {
        return '/halcon/certificado-laboral/'
            . $this->identificacion . '-'
            . $this->usuario . '-'
            . $this->noContrat . '-'
            . $this->ano . '.pdf';
    }
}