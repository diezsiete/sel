<?php


namespace App\Service\ServicioEmpleados;


use App\Entity\ReporteNomina;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use DateTime;
use DateTimeInterface;
use League\Flysystem\FilesystemInterface;
use SSRS\SSRSReportException;

class Reportes
{
    /**
     * @var NovasoftSsrs
     */
    private $novasoftSsrs;
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(NovasoftSsrs $novasoftSsrs, FilesystemInterface $seReportesFilesystem)
    {
        $this->novasoftSsrs = $novasoftSsrs;
        $this->filesystem = $seReportesFilesystem;
    }

    /**
     * @param $empleadoIdent
     * @return ReporteNomina[]|null
     * @throws SSRSReportException
     */
    public function comprobanteList($empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom204();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);

        $nominas = $reporteNovasoft->renderMap();

        krsort($nominas);

        return $nominas;
    }


    public function comprobanteStream($fecha, $empleadoIdent, $ssrsDb)
    {
        if(is_string($fecha)) {
            $fecha = DateTime::createFromFormat('Y-m-d', $fecha);
        }

        return $this->cacheFileStream('comprobante', $fecha, $empleadoIdent, function ($fecha, $empleadoIdent) use ($ssrsDb){
            return $this->novasoftSsrs
                ->setSsrsDb($ssrsDb)
                ->getReportNom204()
                ->setParameterCodigoEmpleado($empleadoIdent)
                ->setParameterFechaInicio($fecha)
                ->setParameterFechaFin($fecha)
                ->renderPdf();
        });
    }


    public function certificadoIngresosList($empleadoIdent)
    {
        $reporteNovasoft = $this->novasoftSsrs->getReportNom92117();
        $reporteNovasoft->setParameterCodigoEmpleado($empleadoIdent);

        $certificados = [];
        $anos = ['2018', '2017'];
        foreach ($anos as $ano) {
            $reporteNovasoft->setParameterAno($ano);
            $certificados[$ano] = $reporteNovasoft->renderCertificado();
        }

        return $certificados;
    }



    public function certificadoIngresosStream(DateTimeInterface $periodo, $empleadoIdent, $ssrsDb)
    {
        return $this->cacheFileStream('certificado-ingresos', $periodo, $empleadoIdent, function ($periodo, $empleadoIdent) use($ssrsDb) {
            return $this->novasoftSsrs
                ->setSsrsDb($ssrsDb)
                ->getReportNom92117()
                ->setParameterCodigoEmpleado($empleadoIdent)
                ->setParameterAno($periodo->format('Y'))
                ->renderPdf();
        });
    }

    private function cacheFileStream(string $reporteNombre, DateTimeInterface $fecha, $empleadoIdent, $callbackSource)
    {
        $path = "/$reporteNombre/" . $empleadoIdent . $fecha->format('Ymd') . '.pdf';
        if (!$this->filesystem->has($path)) {

            $this->filesystem->write($path, $callbackSource($fecha, $empleadoIdent));
        }
        return $this->filesystem->readStream($path);
    }
}