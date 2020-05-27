<?php


namespace App\Service\Napi\Report\Report;

use App\Entity\Main\Usuario;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoIngresos;
use App\Service\Configuracion\Configuracion;
use App\Service\Napi\Report\LocalPdf;
use App\Service\Napi\Report\Report;
use App\Service\Napi\Report\SsrsReport;
use App\Service\Napi\Client\NapiClient;
use App\Service\Pdf\ServicioEmpleados\Report\CertificadoIngresosPdf;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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

    protected function callOperation(Usuario $usuario)
    {
        // TODO
//        $certificadoIngresos = $this->napiClient->itemOperations(CertificadoIngresos::class)->get(
//            $usuario->getIdentificacion(),
//            $this->input->getArgument('year') . '-01-01',
//            $this->input->getArgument('year') . '-12-31'
//        );
        return null;
    }
}