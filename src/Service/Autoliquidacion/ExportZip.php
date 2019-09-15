<?php /** @noinspection PhpComposerExtensionStubsInspection */


namespace App\Service\Autoliquidacion;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Usuario;
use Exception;
use ZipArchive;

class ExportZip extends Export
{

    public function generate(Autoliquidacion $autoliquidacion, ?Usuario $usuario = null)
    {
        $empleados = $this->getAutoliquidacionEmpleadosByRepresentante($autoliquidacion, $usuario);


        $zip = new ZipArchive();
        $zipPath = $this->fileManager->absoluteZipPath($autoliquidacion->getPeriodo(), $autoliquidacion->getConvenio()->getCodigo());
        if ($zip->open($zipPath, ZipArchive::OVERWRITE) !== TRUE) {
            throw new Exception("no se puede abrir <" . $zipPath . ">\n");
        }
        foreach ($empleados as $autoliqEmpleado) {
            if ($archivoPath = $this->fileManager->fileExists($autoliquidacion->getPeriodo(), $autoliqEmpleado->getUsuario()->getIdentificacion())) {
                preg_match('/[^\/]+(?=\/$|$)/', $archivoPath, $matches);
                $zip->addFile($archivoPath, $matches[0]);
            }
        }
        if($zip->status !== 0) {
            throw new Exception("Error zip status 0");
        }
        $zip->close();
        return $zipPath;
    }

    public function stream(Autoliquidacion $autoliquidacion, ?Usuario $usuario = null)
    {
        return null;
    }
}