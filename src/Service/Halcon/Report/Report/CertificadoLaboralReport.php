<?php


namespace App\Service\Halcon\Report\Report;




use App\Entity\Halcon\Report\CertificadoLaboral;
use App\Repository\Halcon\Report\CertificadoLaboralRepository;
use App\Repository\Halcon\TerceroRepository;
use App\Service\Halcon\Report\Importer\CertificadoLaboralImporter;
use App\Service\Pdf\Halcon\CertificadoLaboralPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;

class CertificadoLaboralReport extends Report
{

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


    public function __construct(PdfHandler $pdfHandler, CertificadoLaboralImporter $importer, CertificadoLaboralPdf $certificadoLaboralPdf,
                                CertificadoLaboralRepository $certificadoLaboralRepo, TerceroRepository $terceroRepository)
    {
        parent::__construct($pdfHandler, $importer);
        $this->certificadoLaboralRepo = $certificadoLaboralRepo;
        $this->terceroRepository = $terceroRepository;
        $this->certificadoLaboralPdf = $certificadoLaboralPdf;
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
        $certificado = $this->certificadoLaboralRepo->findCertificado($this->usuario->getIdentificacion(), $this->numeroContrato);
        $tercero = $this->terceroRepository->find($this->usuario->getIdentificacion());
        return $this->certificadoLaboralPdf->build($certificado, $tercero)->Output("S");
    }


    public function getPdfFileName(): string
    {
        return '/halcon/certificado-laboral/' . $this->usuario->getIdentificacion() . '-' . $this->numeroContrato . '.pdf';
    }


    function renderMap()
    {
        foreach($this->certificadoLaboralRepo->findCertificado($this->usuario->getIdentificacion()) as $certificado) {
            // certificados de Halcon sin fecha retiro no los mostramos. Seguramente siguieron trabajando a novasoft
            if($certificado->fechaRetiro) {
                yield $certificado;
            }
        }
    }

    /**
     * @param CertificadoLaboral $reportEntity
     * @return array
     */
    function getIdentifier($reportEntity): array
    {
        return [ $reportEntity->contrato ];
    }
}