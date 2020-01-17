<?php


namespace App\Service\Halcon\Report\Report;




use App\Repository\Halcon\CertificadoIngresosRepository;
use App\Service\Halcon\Report\Importer\CertificadoIngresosImporter;
use App\Service\Pdf\Halcon\CertificadoIngresosPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class CertificadoIngresosReport extends Report
{
    private $noContrat;

    private $ano;

    private $empresaUsuario;

    /**
     * @var CertificadoIngresosRepository
     */
    private $certificadoIngresosRepo;
    /**
     * @var CertificadoIngresosPdf
     */
    private $pdfService;

    public function __construct(PdfHandler $pdfHandler, CertificadoIngresosImporter $importer,
                                CertificadoIngresosRepository $certificadoIngresosRepo,
                                CertificadoIngresosPdf $pdfService)
    {
        parent::__construct($pdfHandler, $importer);
        $this->certificadoIngresosRepo = $certificadoIngresosRepo;
        $this->pdfService = $pdfService;
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

    public function setEmpresaUsuario($empresaUsuario)
    {
        $this->empresaUsuario = $empresaUsuario;
        return $this;
    }

    public function renderMap()
    {
        $criteria = ['nitTercer' => $this->usuario->getIdentificacion()];
        if($this->empresaUsuario) {
            $criteria['usuario'] = $this->empresaUsuario;
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
            $this->usuario->getIdentificacion(), $this->usuario, $this->noContrat, $this->ano
        );

        return $this->pdfService->build($certificado)->Output("S");
    }


    public function getPdfFileName(): string
    {
        return '/halcon/certificado-laboral/'
            . $this->usuario->getIdentificacion() . '-'
            . $this->empresaUsuario . '-'
            . $this->noContrat . '-'
            . $this->ano . '.pdf';
    }

    function getIdentifier($reportEntity): array
    {
        // TODO: Implement getIdentifier() method.
    }
}