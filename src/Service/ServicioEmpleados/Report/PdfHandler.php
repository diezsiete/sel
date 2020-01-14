<?php


namespace App\Service\ServicioEmpleados\Report;


use DateTimeInterface;
use League\Flysystem\FilesystemInterface;

class PdfHandler
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $seReportFilesystem)
    {
        $this->filesystem = $seReportFilesystem;
    }

    public function cache(string $reporteNombre, $id, $callbackSource)
    {
        $path = $this->buildReportPath($reporteNombre, $id);
        if (!$this->filesystem->has($path)) {
            return $this->filesystem->write($path, $callbackSource($id));
        }
        return true;
    }

    public function write(string $reporteNombre, $id, $callbackSource)
    {
        $path = $this->buildReportPath($reporteNombre, $id);
        if ($this->filesystem->has($path)) {
            return $this->filesystem->update($path, $callbackSource($id));
        } else {
            return $this->filesystem->write($path, $callbackSource($id));
        }
    }

    public function readStream(string $reporteNombre, $id)
    {
        return $this->filesystem->readStream($this->buildReportPath($reporteNombre, $id));
    }

    protected function buildReportPath(string $reporteNombre, $id)
    {
        return "/$reporteNombre/$id.pdf";
    }

}