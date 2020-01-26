<?php

namespace App\Controller;

use App\DataTable\SelDataTableFactory;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\ServicioEmpleados\CertificadoLaboralDataTableType;
use App\DataTable\Type\ServicioEmpleados\LiquidacionContratoDataTableType;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\CertificadoLaboral;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\Nomina;
use App\Repository\Main\EmpleadoRepository;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Novasoft\NovasoftEmpleadoService;
use App\Service\ServicioEmpleados\DataTableBuilder;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use App\Service\ServicioEmpleados\Report\ReportFactory as SeReportFactory;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        return new RedirectResponse($reportFactory->nomina($nomina)->linkPdf());
        //return $this->renderStream(function () use ($reportFactory, $nomina) { return $reportFactory->nomina($nomina)->streamPdf();});
    }

    /**
     * @Route("/sel/se/certificado-laboral", name="se_certificado_laboral")
     */
    public function certificadoLaboral(SeReportFactory $seReportFactory, DataTableFactory $dataTableFactory, Request $request, ReportCacheHandler $reportCacheHandler)
    {
        $reportCacheHandler->handle($this->getUser(), CertificadoLaboral::class);
        $parameters = [];

        $certificados = $seReportFactory->certificadoLaboral($this->getUser()->getIdentificacion())->renderMap();

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
        return new RedirectResponse($reportFactory->certificadoLaboral($certificado)->linkPdf());
        //return $this->renderStream(function () use ($reportFactory, $certificado) { return $reportFactory->certificadoLaboral($certificado)->streamPdf();});
    }

    /**
     * @Route("/sel/se/certificado-ingresos", name="se_certificado_ingresos")
     */
    public function certificadoIngresos(DataTableBuilder $dataTableBuilder)
    {
        $table = $dataTableBuilder->certificadoIngresos();

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('servicio_empleados/certificado-ingresos.html.twig', [
            'datatable' => $table
        ]);
    }

    /**
     * @Route("/sel/se/certificado-ingresos/{certificado}", name="se_certificado_ingresos_pdf")
     * @IsGranted("REPORTE_MANAGE", subject="certificado")
     */
    public function certificadoIngresosPdf(SeReportFactory $reportFactory, CertificadoIngresos $certificado)
    {
        return new RedirectResponse($reportFactory->certificadoIngresos($certificado)->linkPdf());
//        return $this->renderStream(function () use ($reportFactory, $certificado) { return $reportFactory->certificadoIngresos($certificado)->streamPdf();});
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
     * @Route("/sel/se/liquidacion-contrato", name="se_liquidacion_contrato")
     */
    public function liquidacionContrato(SelDataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory
            ->createFromServicioEmpleadosType(LiquidacionContratoDataTableType::class, $this->getUser(), ['searching' => false])
            ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('servicio_empleados/liquidacion-contrato.html.twig', [
            'datatable' => $table
        ]);
    }

    /**
     * @Route("/sel/se/liquidacion-contrato/{liquidacion}", name="se_liquidacion_contrato_pdf")
     * @IsGranted("REPORTE_MANAGE", subject="liquidacion")
     */
    public function liquidacionDeContratoPdf(SeReportFactory $reportFactory, LiquidacionContrato $liquidacion)
    {
        return new RedirectResponse($reportFactory->liquidacionContrato($liquidacion)->linkPdf());
        //return $this->renderStream(function () use ($reportFactory, $liquidacion) {return $reportFactory->liquidacionContrato($liquidacion)->streamPdf();});
    }
}
