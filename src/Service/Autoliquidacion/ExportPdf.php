<?php


namespace App\Service\Autoliquidacion;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Main\Usuario;
use FPDI;

class ExportPdf extends Export
{
    public function generate(Autoliquidacion $autoliquidacion, $usuario = null)
    {
        $empleados = $this->getAutoliquidacionEmpleadosByRepresentante($autoliquidacion, $usuario);

        $pdfi = new FPDI();
        $numFiles = 0;
        foreach ($empleados as $autoliqEmpleado) {
            $numFiles++;
            if ($archivoPath = $this->fileManager->fileExists($autoliquidacion->getPeriodo(), $autoliqEmpleado->getUsuario()->getIdentificacion())) {
                $pageCount = $pdfi->setSourceFile($archivoPath);
                for ($i = 0; $i < $pageCount; $i++) {
                    $tpl = $pdfi->importPage($i + 1);
                    $size = $pdfi->getTemplateSize($tpl);
                    // create a page (landscape or portrait depending on the imported page size)
                    if ($size['w'] > $size['h']) {
                        $pdfi->AddPage('L', array($size['w'], $size['h']));
                    } else {
                        $pdfi->AddPage('P', array($size['w'], $size['h']));
                    }
                    $pdfi->useTemplate($tpl);
                }
            }
        }

        $this->fileManager->uploadPdfContents(
            $autoliquidacion->getPeriodo(),
            $autoliquidacion->getConvenio()->getCodigo(),
            $pdfi->Output('S'),
            FileManager::DIR_EXPORT_PDF
        );

        return $this->fileManager->absolutePdfExportPath($autoliquidacion->getPeriodo(), $autoliquidacion->getConvenio()->getCodigo());
    }

    public function stream(Autoliquidacion $autoliquidacion, ?Usuario $usuario = null)
    {
        return $this->fileManager->readStream(
            $autoliquidacion->getPeriodo(),
            $autoliquidacion->getConvenio()->getCodigo(),
            FileManager::DIR_EXPORT_PDF
        );
    }

    public function getSize(Autoliquidacion $autoliquidacion)
    {
        return $this->fileManager->getFileSize(
            $autoliquidacion->getPeriodo(),
            $autoliquidacion->getConvenio()->getCodigo(),
            FileManager::DIR_EXPORT_PDF, 'MB');
    }
}