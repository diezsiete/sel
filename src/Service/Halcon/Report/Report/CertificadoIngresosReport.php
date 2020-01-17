<?php


namespace App\Service\Halcon\Report\Report;




use App\Repository\Halcon\CertificadoIngresosRepository;
use App\Service\Pdf\Halcon\CertificadoIngresosPdf;
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
    /**
     * @var CertificadoIngresosPdf
     */
    private $pdfService;

    public function __construct(PdfHandler $pdfHandler, CertificadoIngresosRepository $certificadoIngresosRepo,
                                CertificadoIngresosPdf $pdfService)
    {
        parent::__construct($pdfHandler);
        $this->certificadoIngresosRepo = $certificadoIngresosRepo;
        $this->pdfService = $pdfService;
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
        $certificado = $this->certificadoIngresosRepo->findCertificado(
            $this->identificacion, $this->usuario, $this->noContrat, $this->ano
        );

        return $this->pdfService->build($certificado)->Output("S");
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