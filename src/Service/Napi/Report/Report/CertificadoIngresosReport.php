<?php


namespace App\Service\Napi\Report\Report;

use App\Entity\Main\Empleado;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoIngresos;
use App\Service\Configuracion\Configuracion;
use App\Service\Excel\Factory;
use App\Service\Napi\Report\LocalPdf;
use App\Service\Napi\Report\SsrsReport;
use App\Service\Napi\Client\NapiClient;
use App\Service\Pdf\ServicioEmpleados\Report\CertificadoIngresosPdf;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

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

    /**
     * @var int
     */
    protected $currentYear;
    /**
     * @var Factory
     */
    private $excelFactory;
    /**
     * @var string
     */
    private $kernelProjectDir;
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    public function __construct(
        NapiClient $client,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        CertificadoIngresosPdf $pdfService,
        Configuracion $configuracion,
        Factory $excelFactory,
        DenormalizerInterface $denormalizer,
        string $kernelProjectDir
    ) {
        parent::__construct($client, $em, $dispatcher, $configuracion);
        $this->pdfService = $pdfService;
        $this->excelFactory = $excelFactory;
        $this->kernelProjectDir = $kernelProjectDir;
        $this->denormalizer = $denormalizer;
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
        $anos = $this->currentYear
            ? [$this->currentYear]
            : $this->configuracion->servicioEmpleados()->getCertificadoIngresosConfig()->getAnos();
        $certificados = [];
        foreach($anos as $ano) {
            if ($ano == '2020') {
                $certificadoIngresos = $this->readFromExcel($ano, $empleado->getIdentificacion());
            } else {
                $certificadoIngresos = $this->client->itemOperations(CertificadoIngresos::class)->get(
                    $empleado->getIdentificacion(),
                    $ano . '-01-01',
                    $ano . '-12-31'
                );
            }
            if($certificadoIngresos) {
                $certificados[] = $certificadoIngresos;
            }
        }
        return $certificados;
    }

    public function setCurrentYear(int $currentYear): self
    {
        $this->currentYear = $currentYear;
        return $this;
    }

    public function readFromExcel(int $ano, $identificacion): ?CertificadoIngresos
    {
        $certificadoIngresos = null;
        $filePath = $this->kernelProjectDir . "/var/uploads/certificado-ingresos/$ano/$identificacion.xlsx";
        if (file_exists($filePath)) {
            $read = $this->excelFactory->read($filePath);
            $read->enableTrim("\xA0\xC2");

            $titles = [
                'numeroFormulario', 'nit', 'dv', 'razonSocial', 'tipoDocumento', 'identificacion', 'primerApellido',
                'segundoApellido', 'primerNombre', 'segundoNombre', 'anoInicial', 'mesInicial', 'diaInicial', 'anoFinal',
                'mesFinal', 'diaFinal', 'anoCert', 'mesCert', 'diaCert', 'ciudad', 'cc1', 'cc2', 'cc3', 'cc4', 'cc5',
                'ingresoSalario', 'bonosElectronicos', 'ingresoHonorarios', 'ingresoServicios', 'ingresoComisiones',
                'ingresoPrestaciones', 'ingresoViaticos', 'ingresoRepresentacion', 'ingresoCompensaciones', 'ingresoOtros',
                'ingresoCesantias', '2020', '2021', 'ingresoPensiones', 'ingresoTotal', 'aportesSalud',
                'aportesObligatoriosPensiones', 'rais', 'covid', 'aportesVoluntariosPensiones', 'aportesAfc', 'valorRetencion'
            ];
            $lastColumn = $read->columnStringFromIndex(array_key_last($titles) + 1);

            $row = $read->row("A1:{$lastColumn}1", $titles);
            $row = $this->formatRowExcel($row);

            $certificadoIngresos = $this->denormalizer->denormalize($row, CertificadoIngresos::class);
        }

        return $certificadoIngresos;
    }

    private function formatRowExcel(array $row): array
    {
        $row['nit'] = (string)$row['nit'];
        $row['dv'] = (string)$row['dv'];
        $row['tipoDocumento'] = (string)$row['tipoDocumento'];
        $row['segundoNombre'] = $row['segundoNombre'] ?: '';
        $row['segundoApellido'] = $row['segundoApellido'] ?: '';
        $row['bonosElectronicos'] = (int)$row['bonosElectronicos'];
        $row['ingresoCesantias'] = (int)$row['ingresoCesantias'];
        $row['identificacion'] = (string)$row['identificacion'];
        $row['fechaInicial'] = "{$row['anoInicial']}-{$row['mesInicial']}-{$row['diaInicial']}";
        $row['fechaFinal'] = "{$row['anoFinal']}-{$row['mesFinal']}-{$row['diaFinal']}";
        $row['fechaExpedicion'] = "{$row['anoCert']}-{$row['mesCert']}-{$row['diaCert']}";
        $row['ciudadCodigo'] = $row['cc1'] . $row['cc2'] . $row['cc3'] . $row['cc4'] . $row['cc5'];
        $row['valorRetencion'] = $row['valorRetencion'] ?: 0;
        unset(
            $row['anoInicial'], $row['mesInicial'], $row['diaInicial'], $row['anoFinal'],
            $row['mesFinal'], $row['diaFinal'], $row['anoCert'], $row['mesCert'], $row['diaCert'],
            $row['cc1'], $row['cc2'], $row['cc3'], $row['cc4'], $row['cc5'], $row[2020], $row[2021]
        );

        return $row;
    }
}