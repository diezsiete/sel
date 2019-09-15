<?php


namespace App\Service\Autoliquidacion;

use DateTimeInterface;
use Exception;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;

class FileManager
{
    const DIR_PDF = "/pdfs";
    const DIR_EXPORT_PDF = "/export/pdf";
    const DIR_EXPORT_ZIP = "/export/zip";
    
    /**
     * @var FilesystemInterface
     */
    private $privateFilesystem;
    private $kernelProjectDir;
    private $privateUploadsBaseUrl;

    public function __construct(FilesystemInterface $privateUploadFilesystem, $kernelProjectDir, $privateUploadsBaseUrl)
    {
        $this->privateFilesystem = $privateUploadFilesystem;
        $this->kernelProjectDir = $kernelProjectDir;
        $this->privateUploadsBaseUrl = $privateUploadsBaseUrl;
    }

    public function uploadPdfResource(DateTimeInterface $periodo, $ident, $resource)
    {
        $path = $this->getPdfPath($periodo, $ident);
        $this->privateFilesystem->putStream($path, $resource);

        if (is_resource($resource)) {
            fclose($resource);
        }
    }

    public function uploadPdfContents(DateTimeInterface $periodo, string $name, string $contents, $dir = null)
    {
        $path = $this->getPdfPath($periodo, $name, $dir);
        $this->privateFilesystem->put($path, $contents);
    }

    public function deletePdf(?DateTimeInterface $periodo = null, $ident = null)
    {
        $path = $this->getPdfPath($periodo, $ident);
        if($this->privateFilesystem->has($path)) {
            $ident ? $this->privateFilesystem->delete($path) : $this->privateFilesystem->deleteDir($path);
        }
    }

    /**
     * @resource
     * @param DateTimeInterface $periodo
     * @param string $ident
     * @param null $dir
     * @return false|resource
     * @throws FileNotFoundException
     */
    public function readStream(DateTimeInterface $periodo, $ident, $dir = null)
    {
        $path = $this->getPdfPath($periodo, $ident, $dir);
        $resource = $this->privateFilesystem->readStream($path);
        if($resource === false) {
            throw new Exception(sprintf("Error abriendo stream para '%s'", $path));
        }
        return $resource;
    }

    /**
     * @param DateTimeInterface $periodo
     * @param $ident
     * @return string|false
     */
    public function fileExists(DateTimeInterface $periodo, $ident)
    {
        $archivoPath = $this->getPdfPath($periodo, $ident);
        return $this->privateFilesystem->has($archivoPath)
            ? $this->kernelProjectDir . $this->privateUploadsBaseUrl . $archivoPath
            : false;
    }

    /**
     * @param DateTimeInterface|null $periodo
     * @param null $ident
     * @param string $dir
     * @return string
     */
    protected function getPdfPath(?DateTimeInterface $periodo = null, $ident = null, $dir = null)
    {
        $dir = $dir ? $dir : static::DIR_PDF;
        $path = "/autoliquidaciones". $dir;
        if($periodo) {
            $periodoDir = $periodo->format('Y-m');
            $path .= "/$periodoDir" . ($ident ? "/$ident.pdf" : "");
        }
        return $path;
    }
}