<?php


namespace App\Service\Novasoft\Report;


use DateTimeInterface;
use League\Flysystem\FilesystemInterface;

/**
 * Class ReportPdfHandler
 * @package App\Service\Novasoft\Report
 * @deprecated TODO BORRAR!!!!!!
 */
class ReportPdfHandler
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $novasoftReportFilesystem)
    {
        $this->filesystem = $novasoftReportFilesystem;
    }

    public function cache(string $reporteNombre, DateTimeInterface $fecha, $empleadoIdent, $callbackSource)
    {
        $path = $this->buildReportPath($reporteNombre, $fecha, $empleadoIdent);
        if (!$this->filesystem->has($path)) {
            return $this->filesystem->write($path, $callbackSource($fecha, $empleadoIdent));
        }
        return true;
    }

    public function write(string $reporteNombre, DateTimeInterface $fecha, $empleadoIdent, $callbackSource)
    {
        $path = $this->buildReportPath($reporteNombre, $fecha, $empleadoIdent);
        if ($this->filesystem->has($path)) {
            return $this->filesystem->update($path, $callbackSource($fecha, $empleadoIdent));
        } else {
            return $this->filesystem->write($path, $callbackSource($fecha, $empleadoIdent));
        }
    }

    public function readStream(string $reporteNombre, DateTimeInterface $fecha, $empleadoIdent)
    {
        return $this->filesystem->readStream($this->buildReportPath($reporteNombre, $fecha, $empleadoIdent));
    }

    protected function buildReportPath(string $reporteNombre, DateTimeInterface $fecha, $empleadoIdent)
    {
        return "/$reporteNombre/" . $empleadoIdent . $fecha->format('Ymd') . '.pdf';
    }

}