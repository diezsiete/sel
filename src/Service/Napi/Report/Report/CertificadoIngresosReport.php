<?php


namespace App\Service\Napi\Report\Report;

use App\Entity\Main\Empleado;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoIngresos;
use App\Service\Configuracion\Configuracion;
use App\Service\Napi\Report\LocalPdf;
use App\Service\Napi\Report\SsrsReport;
use App\Service\Napi\Client\NapiClient;
use App\Service\Pdf\ServicioEmpleados\Report\CertificadoIngresosPdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Previamente en ssrs era el "/ReportesWeb/NOM/NOM921_17"
 * @package App\Service\Napi\Report\Report
 */
class CertificadoIngresosReport extends SsrsReport
{
    use LocalPdf;

    /**
     * @var CertificadoIngresosPdf
     */
    private $pdfService;

    /**
     * @var CertificadoIngresos
     */
    protected $currentReport;

    public function __construct(NapiClient $client, EntityManagerInterface $em, EventDispatcherInterface $dispatcher,
                                CertificadoIngresosPdf $pdfService, Configuracion $configuracion)
    {
        parent::__construct($client, $em, $dispatcher, $configuracion);
        $this->pdfService = $pdfService;
    }

    public function renderPdf(): string
    {
        return $this->pdfService->build($this->currentReport)->Output('S');
    }

    /**
     * @return string
     */
    public function getPdfFileName(): string
    {
        $ano = $this->currentReport->getFechaInicial()->format('Y');
        return 'napi/se/certificado-ingresos/' . $this->currentReport->getIdentificacion() . '-' . $ano . '.pdf';
    }

    protected function callOperation(Empleado $empleado)
    {
        $anos = $this->configuracion->servicioEmpleados()->getCertificadoIngresosConfig()->getAnos();
        $certificados = [];
        foreach($anos as $ano) {
            $certificadoIngresos = $this->client->itemOperations(CertificadoIngresos::class)->get(
                $empleado->getIdentificacion(),
                $ano . '-01-01',
                $ano . '-12-31'
            );
            if($certificadoIngresos) {
                $certificados[] = $certificadoIngresos;
            }
        }
        return $certificados;
    }
}