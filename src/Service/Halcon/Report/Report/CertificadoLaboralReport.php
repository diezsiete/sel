<?php


namespace App\Service\Halcon\Report\Report;




use App\Repository\Halcon\Report\CertificadoLaboralRepository;
use App\Repository\Halcon\TerceroRepository;
use App\Service\Pdf\Halcon\CertificadoLaboralPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class CertificadoLaboralReport extends Report
{
    private $identificacion;

    private $numeroContrato;
    /**
     * @var CertificadoLaboralRepository
     */
    private $certificadoLaboralRepo;
    /**
     * @var TerceroRepository
     */
    private $terceroRepository;
    /**
     * @var CertificadoLaboralPdf
     */
    private $certificadoLaboralPdf;

    public function __construct(PdfHandler $pdfHandler, CertificadoLaboralPdf $certificadoLaboralPdf,
                                CertificadoLaboralRepository $certificadoLaboralRepo, TerceroRepository $terceroRepository)
    {
        parent::__construct($pdfHandler);
        $this->certificadoLaboralRepo = $certificadoLaboralRepo;
        $this->terceroRepository = $terceroRepository;
        $this->certificadoLaboralPdf = $certificadoLaboralPdf;
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
     * @param mixed $numeroContrato
     * @return CertificadoLaboralReport
     */
    public function setNumeroContrato($numeroContrato)
    {
        $this->numeroContrato = $numeroContrato;
        return $this;
    }

    public function renderPdf()
    {
        $certificado = $this->certificadoLaboralRepo->findCertificado($this->identificacion, $this->numeroContrato);
        $tercero = $this->terceroRepository->find($this->identificacion);
        return $this->certificadoLaboralPdf->build($certificado, $tercero)->Output("S");
    }


    public function getPdfFileName(): string
    {
        return '/halcon/certificado-laboral/' . $this->identificacion . '-' . $this->numeroContrato . '.pdf';
    }
}