<?php

namespace App\Controller;


use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\ReporteNominaDataTableType;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\ReporteNomina;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\ReportFactory;
use App\Service\Novasoft\Report\ReportPdfHandler;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\ReportesServicioEmpleados;
use App\Service\ServicioEmpleados\Import;
use App\Service\ServicioEmpleados\Reportes;
use DateInterval;
use DateTime;
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
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(EmpleadoRepository $empleadoRepository, Configuracion $configuracion)
    {
        $this->empleadoRepository = $empleadoRepository;
        $this->configuracion = $configuracion;
    }

    /**
     * @Route("/sel/se/comprobantes", name="app_comprobantes", defaults={"header": "Comprobantes de pago"})

    public function comprobantes(DataTableFactory $dataTableFactory, Request $request, ReportFactory $reportFactory)
    {
        $id = $this->getUser()->getId();
        $table = $dataTableFactory->createFromType(ReporteNominaDataTableType::class,
            ['id' => $id], ['searching' => false, 'paging' => false])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('servicio_empleados/comprobantes.html.twig', ['datatable' => $table]);
    }
    /**
     * @Route("/sel/se/comprobante/{comprobante}", name="app_comprobante")
     * @IsGranted("REPORTE_MANAGE", subject="comprobante")
    public function comprobante(Reportes $reportes, ReporteNomina $comprobante)
    {
        $ssrsDb = $this->getSsrsDb();

        return $this->renderStream(function () use ($reportes, $comprobante, $ssrsDb) {
            return $reportes->comprobanteStream(
                $comprobante->getFecha(),
                $comprobante->getUsuario()->getIdentificacion(),
                $ssrsDb
            );
        });
    }*/

    /**
     * @Route("/sel/se/comprobantes", name="app_comprobantes")
     */
    public function comprobantesNomina(ReportFactory $reportFactory)
    {
        $nominaReport = $reportFactory->getReporteNomina($this->getUser()->getIdentificacion());

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
            $pdfHandler->cache('comprobante', $fecha, $ident, function ($fecha, $ident) use ($reportFactory) {
                return $reportFactory->getReporteNomina($ident, $fecha, $fecha)->renderPdf();
            });
            return $pdfHandler->readStream('comprobante', $fecha, $ident);
        });
    }

    /**
     * @Route("/sel/se/certificado-laboral", name="app_certificado_laboral", defaults={ "header" :"Certificado Laboral"})
     */
    public function certificadoLaboral(ReportesServicioEmpleados $reportes, Configuracion $configuracion)
    {
        $identificacion = $this->getUser()->getIdentificacion();
        $certificados = $reportes->getCertificadosLaborales($identificacion, $this->getSsrsDb());
        return $this->render('servicio_empleados/certificado-laboral.html.twig', [
            'tieneCertificado' => count($certificados) > 0,
        ]);
    }

    /**
     * @Route("/sel/se/certificado-laboral-pdf", name="app_certificado_laboral_pdf")
     */
    public function certificadoLaboralPdf(ReportesServicioEmpleados $reportes, PdfCartaLaboral $pdf)
    {
        $identificacion = $this->getUser()->getIdentificacion();
        $certificado = $reportes->getCertificadoLaboral($identificacion, $this->getSsrsDb());
        if(!$certificado) {
            throw $this->createNotFoundException("Recurso no existe");
        }
        return $this->renderPdf($pdf->render($certificado));
    }

    /**
     * @Route("/sel/se/certificados-ingresos", name="app_certificados_ingresos")
     */
    public function certificadosIngresos(ReportesServicioEmpleados $reportes)
    {
        $identificacion = $this->getUser()->getIdentificacion();
        $certificados = $reportes->getCertificadosIngresos($identificacion, $this->getSsrsDb());
        return $this->render('servicio_empleados/certificado-ingresos.html.twig', [
            'certificados' => $certificados
        ]);
    }

    /**
     * @Route("/sel/se/certificado-ingresos/{periodo}", name="app_certificado_ingresos")
     */
    public function certificadoIngreso(Reportes $reportes, $periodo)
    {
        return $this->renderStream(function () use ($reportes, $periodo) {
            $identificacion = $this->getUser()->getIdentificacion();
            $periodo = DateTime::createFromFormat('Y-m-d', $periodo . "-01-01");
            return $reportes->certificadoIngresosStream($periodo, $identificacion, $this->getSsrsDb());
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
        if(count($this->configuracion->getSsrsDb()) === 1) {
            return $this->configuracion->getSsrsDb()[0]->getNombre();
        }

        $empleado = $this->empleadoRepository->findByIdentificacion($this->getUser()->getIdentificacion());
        if($empleado) {
            return $empleado->getSsrsDb();
        } else {
            return $this->configuracion->getSsrsDb()[0]->getNombre();
        }
    }
}
