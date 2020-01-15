<?php

namespace App\Controller;

use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\ServicioEmpleados\CertificadoLaboralDataTableType;
use App\DataTable\Type\ServicioEmpleados\NominaDataTableType;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\ServicioEmpleados\CertificadoLaboral;
use App\Entity\ServicioEmpleados\Nomina;
use App\Repository\Main\EmpleadoRepository;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\Novasoft\Report\ReportFactory;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\ServicioEmpleados\Report\ReportFactory as SeReportFactory;
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
     * @Route("/sel/se/comprobantes", name="se_comprobantes")
     */
    public function comprobantes(DataTableFactory $dataTableFactory, Request $request)
    {
        $id = $this->getUser()->getId();
        $table = $dataTableFactory
            ->createFromType(NominaDataTableType::class, ['id' => $id], ['searching' => false])
            ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('servicio_empleados/comprobantes.html.twig', ['datatable' => $table]);
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

    /**
     * @Route("/sel/se/certificado-laboral", name="se_certificado_laboral")
     */
    public function certificadoLaboral(SeReportFactory $seReportFactory, DataTableFactory $dataTableFactory, Request $request)
    {
        $parameters = [];
        $identificacion = $this->getUser()->getIdentificacion();
        $certificados = $seReportFactory->certificadoLaboral($identificacion)->renderMap();
        if(count($certificados) > 1) {
            $table = $dataTableFactory
                ->createFromType(CertificadoLaboralDataTableType::class, ['id' => $this->getUser()->getId()], ['searching' => false])
                ->handleRequest($request);
            if($table->isCallback()) {
                return $table->getResponse();
            }
            $parameters['datatable'] = $table;
        } else {
            $parameters['certificado'] = $certificados ? $certificados[0] : null;
        }

        return $this->render('servicio_empleados/certificado-laboral.html.twig', $parameters);
    }

    /**
     * @Route("/sel/se/certificado-laboral/{certificado}", name="se_certificado_laboral_pdf")
     * @IsGranted("REPORTE_MANAGE", subject="certificado")
     */
    public function certificadoLaboralPdf(SeReportFactory $reportFactory, CertificadoLaboral $certificado)
    {
        return $this->renderStream(function () use ($reportFactory, $certificado) {
            return $reportFactory->certificadoLaboral($certificado)->streamPdf();
        });
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
    public function certificadoIngreso(ReportFactory $reportFactory, PdfHandler $pdfHandler, $periodo)
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
    public function liquidacionesDeContrato(ReportFactory $reportFactory)
    {
        $identificacion = $this->getUser()->getIdentificacion();

        $report = $reportFactory->liquidacionContrato($identificacion, $this->getSsrsDb());
        $liquidaciones = $report->renderMap();

        return $this->render('servicio_empleados/liquidaciones-de-contrato.html.twig', [
            'liquidaciones' => $liquidaciones
        ]);
    }

    /**
     * @Route("/sel/se/liquidacion-de-contrato/{fechaIngreso}/{fechaRetiro}", name="app_liquidacion_de_contrato_pdf")
     */
    public function liquidacionDeContratoPdf(ReportFactory $reportFactory, $fechaIngreso, $fechaRetiro, PdfHandler $pdfHandler)
    {
        return $this->renderStream(function () use ($reportFactory, $fechaIngreso, $fechaRetiro, $pdfHandler) {
            $identificacion = $this->getUser()->getIdentificacion();
            $fechaIngreso = DateTime::createFromFormat('Y-m-d', $fechaIngreso);
            $fechaRetiro = DateTime::createFromFormat('Y-m-d', $fechaRetiro);


            $pdfHandler->write('liquidacion-contrato', $fechaIngreso, $identificacion,
                function (DateTimeInterface $fechaIngreso, $ident) use ($reportFactory, $fechaRetiro) {
                    $report = $reportFactory->liquidacionContrato($ident, $this->getSsrsDb())
                        ->setParameterFechaInicio($fechaIngreso)
                        ->setParameterFechaFin($fechaRetiro);
                    return $report->renderPdf();
                }
            );

            return $pdfHandler->readStream('liquidacion-contrato', $fechaIngreso, $identificacion);
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
