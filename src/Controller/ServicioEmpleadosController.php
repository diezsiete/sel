<?php

namespace App\Controller;


use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\Novasoft\Report\NominaDataTableType;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Halcon\Vinculacion;
use App\Repository\Halcon\VinculacionRepository;
use App\Repository\Main\EmpleadoRepository;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\ReportFactory;
use App\Service\Novasoft\Report\ReportPdfHandler;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\ReportesServicioEmpleados;
use App\Service\ServicioEmpleados\Reportes;
use DateTime;
use DateTimeInterface;
use Exception;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServicioEmpleadosController extends BaseController
{
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;

    /**
     * @var NovasoftEmpleadoService
     */
    private $novasoftEmpleadoService;

    public function __construct(EmpleadoRepository $empleadoRepository, NovasoftEmpleadoService $novasoftEmpleadoService)
    {
        $this->empleadoRepository = $empleadoRepository;

        $this->novasoftEmpleadoService = $novasoftEmpleadoService;
    }

    /**
     * @Route("/sel/se/comprobantes2", name="se_comprobantes")
     */
    public function comprobantes(DataTableFactory $dataTableFactory, Request $request)
    {
        $id = $this->getUser()->getId();
        $table = $dataTableFactory->createFromType(NominaDataTableType::class,
            ['id' => $id], ['searching' => false, 'paging' => false])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('servicio_empleados/comprobantes.html.twig', ['datatable' => $table]);
    }
    /**
     * @Route("/sel/se/comprobante/{source}/{comprobante}", name="se_comprobante", defaults={"source"="novasoft"})
     * @IsGranted("REPORTE_MANAGE", subject="comprobante")
     */
    public function comprobante(\App\Service\ServicioEmpleados\ReportFactory $reportFactory)
    {
        /*$ssrsDb = $this->getSsrsDb();

        return $this->renderStream(function () use ($reportes, $comprobante, $ssrsDb) {
            return $reportes->comprobanteStream(
                $comprobante->getFecha(),
                $comprobante->getUsuario()->getIdentificacion(),
                $ssrsDb
            );
        });*/
    }

    /**
     * @Route("/sel/se/comprobantes", name="app_comprobantes")
     */
    public function comprobantesNomina(ReportFactory $reportFactory)
    {
        $nominaReport = $reportFactory->getReporteNomina($this->getUser()->getIdentificacion(), null, null, $this->getSsrsDb());

        return $this->render('servicio_empleados/comprobantes-temp.html.twig', [
            'comprobantes' => $nominaReport->renderMap()
        ]);
    }

    /**
     * @Route("/sel/se/comprobante/{periodo}", name="app_comprobante")
     */
    public function comprobanteNomina(ReportFactory $reportFactory, ReportPdfHandler $pdfHandler, $periodo)
    {
        return $this->renderStream(function () use ($reportFactory, $pdfHandler, $periodo) {
            $fecha = DateTime::createFromFormat('Ymd', $periodo);
            $ident = $this->getUser()->getIdentificacion();
            // usar write si no se quiere cache
            $pdfHandler->write('comprobante', $fecha, $ident, function ($fecha, $ident) use ($reportFactory) {
                return $reportFactory->getReporteNomina($ident, $fecha, $fecha, $this->getSsrsDb())->renderPdf();
            });
            return $pdfHandler->readStream('comprobante', $fecha, $ident);
        });
    }

    /**
     * @Route("/sel/se/certificado-laboral", name="app_certificado_laboral")
     */
    public function certificadoLaboral(ReportFactory $reportFactory)
    {
        $identificacion = $this->getUser()->getIdentificacion();

        $certificadoReport = $reportFactory->certificadoLaboral($identificacion, $this->getSsrsDb());
        $certificados = $certificadoReport->renderMap();

        return $this->render('servicio_empleados/certificado-laboral.html.twig', [
            'tieneCertificado' => count($certificados),
        ]);
    }

    /**
     * @Route("/sel/se/certificado-laboral-pdf", name="app_certificado_laboral_pdf")
     */
    public function certificadoLaboralPdf(ReportFactory $reportFactory, PdfCartaLaboral $pdf)
    {
        $certificados = $reportFactory
            ->certificadoLaboral($this->getUser()->getIdentificacion(), $this->getSsrsDb())
            ->renderMap();
        if(!$certificados) {
            throw $this->createNotFoundException("Recurso no existe");
        }
        return $this->renderPdf($pdf->render($certificados[0]));
    }

    /**
     * @Route("/sel/se/certificados-ingresos", name="app_certificados_ingresos")
     */
    public function certificadosIngresos(ReportFactory $reportFactory)
    {
        $identificacion = $this->getUser()->getIdentificacion();

        $certificados = [];
        $anos = ["2018", "2017"];
        foreach ($anos as $ano) {
            $report = $reportFactory->certificadoIngresos($ano, $identificacion, $this->getSsrsDb());
            $certificado = $report->renderMap();
            if($certificado) {
                $certificados[$ano] = $certificado;
            }
        }

        return $this->render('servicio_empleados/certificado-ingresos.html.twig', [
            'certificados' => $certificados
        ]);
    }

    /**
     * @Route("/sel/se/certificado-ingresos/{periodo}", name="app_certificado_ingresos")
     */
    public function certificadoIngreso(ReportFactory $reportFactory, ReportPdfHandler $pdfHandler, $periodo)
    {
        return $this->renderStream(function () use ($reportFactory, $pdfHandler, $periodo) {
            $identificacion = $this->getUser()->getIdentificacion();

            $fecha = DateTime::createFromFormat('Y-m-d', $periodo . "-01-01");

            $pdfHandler->write('certificado-ingresos', $fecha, $identificacion,
                function (DateTimeInterface $fecha, $ident) use ($reportFactory) {
                    return $reportFactory->certificadoIngresos($fecha->format('Y'), $ident, $this->getSsrsDb())->renderPdf();
                }
            );

            return $pdfHandler->readStream('certificado-ingresos', $fecha, $identificacion);

        });
    }

    /**
     * @Route("/sel/se/certificados-aportes", name="app_certificados_aportes")
     */
    public function certificadosAportes(DataTableFactory $dataTableFactory, Request $request)
    {
        $id = $this->getUser()->getId();
        $table = $dataTableFactory->createFromType(AutoliquidacionEmpleadoDataTableType::class,
            ['id' => $id], ['searching' => false, 'paging' => false])
            ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('servicio_empleados/certificados-aportes.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/se/certificado-aporte/{id}", name="app_certificado_aporte")
     * @IsGranted("AUTOLIQUIDACION_MANAGE", subject="autoliquidacionEmpleado")
     */
    public function certificadoAporte(AutoliquidacionEmpleado $autoliquidacionEmpleado, FileManager $autoliquidacionService)
    {
        return $this->renderStream(function () use ($autoliquidacionEmpleado, $autoliquidacionService) {
            return $autoliquidacionService->readStream(
                $autoliquidacionEmpleado->getAutoliquidacion()->getPeriodo(),
                $autoliquidacionEmpleado->getEmpleado()->getUsuario()->getIdentificacion()
            );
        });
    }

    /**
     * @Route("/sel/se/liquidaciones-de-contrato", name="app_liquidaciones_de_contrato")
     */
    public function liquidacionesDeContrato(ReportesServicioEmpleados $reportes)
    {
        $identificacion = $this->getUser()->getIdentificacion();
        $liquidaciones = $reportes->getLiquidacionesDeContrato($identificacion);
        return $this->render('servicio_empleados/liquidaciones-de-contrato.html.twig', [
            'liquidaciones' => $liquidaciones
        ]);
    }

    /**
     * @Route("/sel/se/liquidacion-de-contrato/{fechaIngreso}/{fechaRetiro}", name="app_liquidacion_de_contrato_pdf")
     */
    public function liquidacionDeContratoPdf(Reportes $reportes, $fechaIngreso, $fechaRetiro)
    {
        return $this->renderStream(function () use ($reportes, $fechaIngreso, $fechaRetiro) {
            $identificacion = $this->getUser()->getIdentificacion();
            $fechaIngreso = DateTime::createFromFormat('Y-m-d', $fechaIngreso);
            $fechaRetiro = DateTime::createFromFormat('Y-m-d', $fechaRetiro);
            return $reportes->getLiquidacionStream($identificacion, $fechaIngreso, $fechaRetiro, $this->getSsrsDb());
        });
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getSsrsDb()
    {
        return $this->novasoftEmpleadoService->getSsrsDb($this->getUser()->getIdentificacion());
    }
}
