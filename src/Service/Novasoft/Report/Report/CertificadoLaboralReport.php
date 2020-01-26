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
use Psr\Container\ContainerInterface;
use SSRS\SSRSReport;
use SSRS\SSRSReportException;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * Class CertificadoLaboralReport
 * @package App\Service\Novasoft\Report\Report
 */
class CertificadoLaboralReport extends Report implements ServiceSubscriberInterface
{

    protected $path = "/ReportesWeb/NOM/NOM932";

    /**
     * Identificación del empleado
     * @var string
     */
    protected $parameter_cod_emp;
    /**
     * @var ContainerInterface
     */
    private $container;


    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion, ContainerInterface $container,
                                Utils $utils, CertificadoLaboralMapper $mapper, CertificadoLaboralImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
        $this->container = $container;
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
        return $this->reportFormatter->setSsrsDb($this->db)->mapCsv($csvAssociative, $this->mapper);
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
        return $this->container->get(PdfCartaLaboral::class)->render($map[0])->Output("S");
    }

    /**
     * @param CertificadoLaboral $certificadoLaboral
     * @return CertificadoLaboralReport
     */
    public function setParametersByEntity($certificadoLaboral)
    {
        $this
            ->setParameterCodigoEmpleado($certificadoLaboral->getUsuario()->getIdentificacion())
            ->setDb($certificadoLaboral->getSsrsDb() ? $certificadoLaboral->getSsrsDb() : $this->db);
        return $this;
    }


    public static function getSubscribedServices()
    {
        return [
            PdfCartaLaboral::class
        ];
    }
}