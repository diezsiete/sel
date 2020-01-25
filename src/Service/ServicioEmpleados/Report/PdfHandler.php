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


    public function __construct(FilesystemInterface $seReportCachedFilesystem)
    {
        //$this->filesystem = $seReportFilesystem;
        $this->filesystem = $seReportCachedFilesystem;
    }

    public function cache(string $reporteNombre, $callbackSource)
    {
        $path = $reporteNombre;
        if (!$this->filesystem->has($path)) {
            return $this->filesystem->write($path, $callbackSource());
        }
        return true;
    }

    public function write(string $reporteNombre, $callbackSource)
    {
        $path = $reporteNombre;
        if ($this->filesystem->has($path)) {
            return $this->filesystem->update($path, $callbackSource());
        } else {
            return $this->filesystem->write($path, $callbackSource());
        }
    }

    public function writeAndStream(string $reporteNombre, $callbackSource)
    {
        $this->write($reporteNombre, $callbackSource);
        return $this->readStream($reporteNombre);
    }

    public function cacheAndStream(string $reporteNombre, $callbackSource)
    {
        $this->cache($reporteNombre, $callbackSource);
        return $this->readStream($reporteNombre);
    }

    public function readStream(string $reporteNombre)
    {
        return $this->filesystem->readStream($reporteNombre);
    }

    public function delete(string $reportNombre)
    {
        $reportDeleted = null;
        if($this->filesystem->has($reportNombre)) {
            $reportDeleted = $reportNombre;
            $this->filesystem->delete($reportNombre);
        }
        return $reportDeleted;
    }

}