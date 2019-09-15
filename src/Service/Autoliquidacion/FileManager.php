<?php


namespace App\Service\Autoliquidacion;


use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use DateTimeInterface;
use Exception;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;

class FileManager
{
    /**
     * @var FilesystemInterface
     */
    private $privateFilesystem;

    public function __construct(FilesystemInterface $privateUploadFilesystem)
    {
        $this->privateFilesystem = $privateUploadFilesystem;
    }

    public function uploadPdfResource(DateTimeInterface $periodo, $ident, $resource)
    {
        $path = $this->getPdfPath($periodo, $ident);
        $this->privateFilesystem->putStream($path, $resource);

        if (is_resource($resource)) {
            fclose($resource);
        }
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
     * @param DateTimeInterface|AutoliquidacionEmpleado $periodo
     * @param string|null $ident
     * @return false|resource
     * @throws FileNotFoundException
     */
    public function readStream($periodo, $ident = null)
    {
        if($ident === null || $periodo instanceof AutoliquidacionEmpleado) {
            $autoliquidacionEmpleado = $periodo;
            $periodo = $autoliquidacionEmpleado->getAutoliquidacion()->getPeriodo();
            $ident = $autoliquidacionEmpleado->getEmpleado()->getUsuario()->getIdentificacion();
        }

        $path = $this->getPdfPath($periodo, $ident);
        $resource = $this->privateFilesystem->readStream($path);
        if($resource === false) {
            throw new Exception(sprintf("Error abriendo stream para '%s'", $path));
        }
        return $resource;
    }

    protected function getPdfPath(?DateTimeInterface $periodo = null, $ident = null)
    {
        $path = "/autoliquidaciones/pdfs/";
        if($periodo) {
            $periodoDir = $periodo->format('Y-m');
            $path .= "/$periodoDir" . ($ident ? "/$ident.pdf" : "");
        }
        return $path;
    }
}