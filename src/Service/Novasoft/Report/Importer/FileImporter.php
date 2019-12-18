<?php


namespace App\Service\Novasoft\Report\Importer;


use League\Flysystem\FilesystemInterface;

class FileImporter
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $novasoftReportFilesystem)
    {
        $this->filesystem = $novasoftReportFilesystem;
    }

    public function write(string $reporteNombre, string $identifier, string $fecha, string $ext, $source)
    {
        $path = "/$reporteNombre/" . $identifier . "-" . $fecha . '.' . $ext;
        if ($this->filesystem->has($path)) {
            $this->filesystem->delete($path);
        }
        $this->filesystem->write($path, $source);
    }
}