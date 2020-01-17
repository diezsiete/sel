<?php

namespace App\Controller;

use App\DataTable\SelDataTableFactory;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\ServicioEmpleados\CertificadoIngresosDataTableType;
use App\DataTable\Type\ServicioEmpleados\CertificadoLaboralDataTableType;
use App\DataTable\Type\ServicioEmpleados\LiquidacionContratoDataTableType;
use App\DataTable\Type\ServicioEmpleados\NominaDataTableType;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\CertificadoLaboral;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\Nomina;
use App\Repository\Main\EmpleadoRepository;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\ReportFactory;
use App\Service\Novasoft\Report\ReportPdfHandler;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\ReportesServicioEmpleados;
use App\Service\ServicioEmpleados\DataTableBuilder;
use App\Service\ServicioEmpleados\Report\ReportFactory as SeReportFactory;
use App\Service\ServicioEmpleados\Reportes;
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
     * @var NovasoftEmpleadoService
     */
    private $novasoftEmpleadoService;

    public function __construct(EmpleadoRepository $empleadoRepository, NovasoftEmpleadoService $novasoftEmpleadoService)
    {
        $this->empleadoRepository = $empleadoRepository;
        $this->novasoftEmpleadoService = $novasoftEmpleadoService;
    }

    /**
     * @Route("/sel/se/comprobantes", name="se_comprobantes")
     */
    public function comprobantes(DataTableBuilder $dataTableBuilder)
    {
        $table = $dataTableBuilder->nomina();

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('servicio_empleados/comprobantes.html.twig', [
            'datatable' => $table
        ]);
    }
    /**
     * @Route("/sel/se/comprobante/{nomina}", name="se_comprobante")
     * @IsGranted("REPORTE_MANAGE", subject="nomina")
     */
    public function comprobante(SeReportFactory $reportFactory, Nomina $nomina)
    {
        return $this->renderStream(function () use ($reportFactory, $nomina) {
            return $reportFactory->nomina($nomina)->streamPdf();
        });
    }



//    /**
//     * @Route("/sel/se/certificado-laboral", name="se_certificado_laboral")
//     */
//    public function certificadoLaboral(SeReportFactory $seReportFactory, DataTableFactory $dataTableFactory, Request $request)
//    {
//        $parameters = [];
//        $identificacion = $this->getUser()->getIdentificacion();
//        $certificados = $seReportFactory->certificadoLaboral($identificacion)->renderMap();
//        if(count($certificados) > 1) {
//            $table = $dataTableFactory
//                ->createFromType(CertificadoLaboralDataTableType::class, ['id' => $this->getUser()->getId()], ['searching' => false])
//                ->handleRequest($request);
//            if($table->isCallback()) {
//                return $table->getResponse();
//            }
//            $parameters['datatable'] = $table;
//        } else {
//            $parameters['certificado'] = $certificados ? $certificados[0] : null;
//        }
//
//        return $this->render('servicio_empleados/certificado-laboral.html.twig', $parameters);
//    }

//    /**
//     * @Route("/sel/se/certificado-laboral/{certificado}", name="se_certificado_laboral_pdf")
//     * @IsGranted("REPORTE_MANAGE", subject="certificado")
//     */
//    public function certificadoLaboralPdf(SeReportFactory $reportFactory, CertificadoLaboral $certificado)
//    {
//        return $this->renderStream(function () use ($reportFactory, $certificado) {
//            return $reportFactory->certificadoLaboral($certificado)->streamPdf();
//        });
//    }

//    /**
//     * @Route("/sel/se/certificados-ingresos", name="se_certificado_ingresos")
//     */
//    public function certificadoIngresos(DataTableFactory $dataTableFactory, Request $request)
//    {
//        $id = $this->getUser()->getId();
//        $table = $dataTableFactory
//            ->createFromType(CertificadoIngresosDataTableType::class, ['id' => $id], ['searching' => false])
//            ->handleRequest($request);
//
//        if($table->isCallback()) {
//            return $table->getResponse();
//        }
//
//        return $this->render('servicio_empleados/certificado-ingresos.html.twig', [
//            'datatable' => $table
//        ]);
//    }

//    /**
//     * @Route("/sel/se/certificado-ingresos/{certificado}", name="se_certificado_ingresos_pdf")
//     * @IsGranted("REPORTE_MANAGE", subject="certificado")
//     */
//    public function certificadoIngresosPdf(SeReportFactory $reportFactory, CertificadoIngresos $certificado)
//    {
//        return $this->renderStream(function () use ($reportFactory, $certificado) {
//            return $reportFactory->certificadoIngresos($certificado)->streamPdf();
//        });
//    }

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

//    /**
//     * @Route("/sel/se/liquidaciones-de-contrato", name="app_liquidaciones_de_contrato")
//     */
//    public function liquidacionesDeContrato(SelDataTableFactory $dataTableFactory, Request $request)
//    {
//
//        $table = $dataTableFactory
//            ->createFromServicioEmpleadosType(LiquidacionContratoDataTableType::class, $this->getUser(), ['searching' => false])
//            ->handleRequest($request);
//
//        if($table->isCallback()) {
//            return $table->getResponse();
//        }
//
//        return $this->render('servicio_empleados/liquidaciones-de-contrato.html.twig', [
//            'datatable' => $table
//        ]);
//    }

//    /**
//     * @Route("/sel/se/liquidacion-de-contrato/{liquidacion}", name="se_liquidacion_de_contrato_pdf")
//     * @IsGranted("REPORTE_MANAGE", subject="liquidacion")
//     */
//    public function liquidacionDeContratoPdf(SeReportFactory $reportFactory, LiquidacionContrato $liquidacion)
//    {
//        $reportFactory->liquidacionContrato($liquidacion)->renderPdf();
////        return $this->renderStream(function () use ($reportFactory, $liquidacion) {
////            return $reportFactory->liquidacionContrato($liquidacion)->streamPdf();
////        });
//    }


    /**
     * @Route("/sel/se/certificado-laboral", name="app_certificado_laboral", defaults={ "header" :"Certificado Laboral"})
     */
    public function certificadoLaboral(ReportesServicioEmpleados $reportes)
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
