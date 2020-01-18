<?php


namespace App\Service\Novasoft\Report\Report;


use App\Entity\Main\Usuario;
use App\Entity\Novasoft\Report\CertificadoLaboral;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\CertificadoLaboralImporter;
use App\Service\Novasoft\Report\Mapper\CertificadoLaboralMapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\Utils;
use SSRS\SSRSReport;
use SSRS\SSRSReportException;

/**
 * Class CertificadoLaboralReport
 * @package App\Service\Novasoft\Report\Report
 */
class CertificadoLaboralReport extends Report
{

    protected $path = "/ReportesWeb/NOM/NOM932";

    /**
     * Identificación del empleado
     * @var string
     */
    protected $parameter_cod_emp;
    /**
     * @var PdfCartaLaboral
     */
    private $pdf;


    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion, PdfCartaLaboral $pdf,
                                Utils $utils, CertificadoLaboralMapper $mapper, CertificadoLaboralImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
        $this->pdf = $pdf;
    }

    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_cod_emp = $identificacion;
        return $this;
    }

    /**
     * @return CertificadoLaboral[]
     * @throws SSRSReportException
     */
    public function renderMap()
    {
        $csvAssociative = $this->reportFormatter->csvColsSplittedToAssociative($this->renderCSV());
        return $this->reportFormatter->mapCsv($csvAssociative, $this->mapper);
    }

    public function getPdfFileName(): string
    {
        return 'novasoft/certificado-laboral/' . $this->parameter_cod_emp . '.pdf';
    }

    public function setUsuario(Usuario $usuario)
    {
        parent::setUsuario($usuario);
        $this->parameter_cod_emp = $usuario->getIdentificacion();
        return $this;
    }

    public function renderPdf()
    {
        $map = $this->renderMap();
        return $this->pdf->render($map[0])->Output("S");
    }
}