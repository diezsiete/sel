<?php


namespace App\Service\Autoliquidacion;

use App\Service\Utils;
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
    /**
     * @var Utils
     */
    private $utils;

    public function __construct(FilesystemInterface $privateUploadFilesystem, $kernelProjectDir, $privateUploadsBaseUrl, Utils $utils)
    {
        $this->privateFilesystem = $privateUploadFilesystem;
        $this->kernelProjectDir = $kernelProjectDir;
        $this->privateUploadsBaseUrl = $privateUploadsBaseUrl;
        $this->utils = $utils;
    }

    public function uploadPdfResource(DateTimeInterface $periodo, $ident, $resource)
    {
        $path = $this->getPath($periodo, $ident);
        $this->privateFilesystem->putStream($path, $resource);

        if (is_resource($resource)) {
            fclose($resource);
        }
    }

    public function uploadPdfContents(DateTimeInterface $periodo, string $name, string $contents, $dir = null)
    {
        $path = $this->getPath($periodo, $name, $dir);
        $this->privateFilesystem->put($path, $contents);
    }

    public function deletePdf(?DateTimeInterface $periodo = null, $ident = null)
    {
        $path = $this->getPath($periodo, $ident);
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
        $path = $this->getPath($periodo, $ident, $dir);
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
        $archivoPath = $this->getPath($periodo, $ident);
        return $this->privateFilesystem->has($archivoPath)
            ? $this->getBasePath() . $archivoPath
            : false;
    }

    public function getBasePath()
    {
        return $this->kernelProjectDir . $this->privateUploadsBaseUrl;
    }

    /**
     * @param DateTimeInterface|null $periodo
     * @param null $ident
     * @param string $dir
     * @return string
     */
    public function getPath(?DateTimeInterface $periodo = null, $ident = null, $dir = null)
    {
        $extension = 'pdf';
        $dir = $dir ? $dir : static::DIR_PDF;
        if($dir === static::DIR_EXPORT_ZIP) {
            $extension = 'zip';
        }
        $path = "/autoliquidaciones". $dir;
        if($periodo) {
            $periodoDir = $periodo->format('Y-m');
            $path .= "/$periodoDir" . ($ident ? "/$ident.{$extension}" : "");
        }
        return $path;
    }

    public function absoluteZipPath(DateTimeInterface $periodo, string $codigo)
    {
        $path = $this->getPath($periodo, $codigo, static::DIR_EXPORT_ZIP);
        if (!$this->privateFilesystem->has($path)) {
            $this->privateFilesystem->write($path, "");
        }
        return $this->kernelProjectDir . $this->privateUploadsBaseUrl . $path;
    }

    public function absolutePdfExportPath(DateTimeInterface $periodo, string $codigo)
    {
        $path = $this->getPath($periodo, $codigo, static::DIR_EXPORT_PDF);
        return $this->kernelProjectDir . $this->privateUploadsBaseUrl . $path;
    }

    public function getFileSize(DateTimeInterface $periodo, $name, $dir = null, $format = 'B')
    {
        $size = $this->privateFilesystem->getSize($this->getPath($periodo, $name, $dir));
        return $format === 'B' ? $size : $this->utils->byteToSize($size, 1, $format);
    }
}