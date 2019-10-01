<?php

namespace App\Controller;


use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\ReporteNominaDataTableType;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\ReporteNomina;
use App\Repository\ConvenioRepository;
use App\Repository\ReporteNominaRepository;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Configuracion\Configuracion;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use App\Service\Pdf\PdfCartaLaboral;
use App\Service\ReportesServicioEmpleados;
use App\Service\ServicioEmpleados\Reportes;
use DateTime;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServicioEmpleadosController extends BaseController
{
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;

    public function __construct(ConvenioRepository $convenioRepository)
    {
        $this->convenioRepository = $convenioRepository;
    }

    /**
     * @Route("/sel/se/comprobantes", name="app_comprobantes", defaults={"header": "Comprobantes de pago"})
     */
    public function comprobantes(DataTableFactory $dataTableFactory, Request $request, NovasoftSsrs $novasoftSsrs,
                                 ReporteNominaRepository $reporteNominaRepository)
    {
        $id = $this->getUser()->getId();
        $table = $dataTableFactory->createFromType(ReporteNominaDataTableType::class,
            ['id' => $id], ['searching' => false, 'paging' => false])
            ->handleRequest($request);

        if($table->isCallback()) {
            $desde = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m') . '-01');
            $hasta = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m-t'));

            $reportesNomina = $novasoftSsrs
                ->setSsrsDb($this->getSsrsDb())
                ->getReporteNomina($this->getUser(), $desde, $hasta);

            if(count($reportesNomina)) {
                foreach ($reportesNomina as $reporteNomina) {
                    $reporteNominaDb = $reporteNominaRepository->findByFecha($reporteNomina->getUsuario(), $reporteNomina->getFecha());
                    if ($reporteNominaDb) {
                        $this->em()->remove($reporteNominaDb);
                    }
                    $this->em()->persist($reporteNomina);
                }
                $this->em()->flush();
            }

            return $table->getResponse();
        }

        return $this->render('servicio_empleados/comprobantes.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/se/comprobante/{comprobante}", name="app_comprobante")
     * @IsGranted("REPORTE_MANAGE", subject="comprobante")
     */
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
            $periodo = \DateTime::createFromFormat('Y-m-d', $periodo . "-01-01");
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
     * @IsGranted("REPORTE_MANAGE", subject="autoliquidacionEmpleado")
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
    public function liquidacionDeContratoPdf(ReportesServicioEmpleados $reportes, $fechaIngreso, $fechaRetiro)
    {
        $identificacion = $this->getUser()->getIdentificacion();
        $reportePdf = $reportes->getLiquidacionDeContratoPdf($identificacion, $fechaIngreso, $fechaRetiro);
        return $this->renderPdf($reportePdf);
    }

    /**
     * @return string|null
     */
    private function getSsrsDb()
    {
        $convenio = $this->convenioRepository->findConvenioByIdent($this->getUser()->getIdentificacion());
        if($convenio) {
            return $convenio->getSsrsDb();
        } else {
            throw $this->createNotFoundException();
        }
    }
}
