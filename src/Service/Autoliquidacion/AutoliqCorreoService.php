<?php


namespace App\Service\Autoliquidacion;

use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Main\Representante;
use \App\Service\Halcon\Servicios\Correo as HalconCorreo;
use App\Service\File\FileManager as FileManagerService;

class AutoliqCorreoService
{
    /**
     * @var HalconCorreo
     */
    private $halconCorreo;
    /**
     * @var FileManager
     */
    private $autoliqFileManager;
    /**
     * @var FileManagerService
     */
    private $fileManagerService;

    public function __construct(HalconCorreo $halconCorreo, FileManager $autoliqFileManager, FileManagerService $fileManagerService)
    {
        $this->halconCorreo = $halconCorreo;
        $this->autoliqFileManager = $autoliqFileManager;
        $this->fileManagerService = $fileManagerService;
    }

    public function adjuntoLimite()
    {
        return $this->halconCorreo->adjuntoLimite();
    }

    /**
     * @param Autoliquidacion $autoliquidacion
     * @return Representante[]
     */
    public function getRecipients(Autoliquidacion $autoliquidacion)
    {
        $convenio = $autoliquidacion->getConvenio();
        $encargados = $convenio->getEncargados();
        if (!$encargados->count()) {
            if ($bcc = $convenio->getBcc()->first()) {
                $encargados[] = $bcc;
            }
        }
        return $encargados->toArray();
    }

    /**
     * @param Autoliquidacion $autoliquidacion
     * @return string[]
     */
    public function getBccsEmails(Autoliquidacion $autoliquidacion)
    {
        $convenio = $autoliquidacion->getConvenio();
        $encargados = $convenio->getEncargados();
        $convenioBccs = $convenio->getBcc();
        if (!$encargados->count()) {
            $bccs = [];
            for ($i = 0, $iMax = count($convenioBccs); $i < $iMax; $i++) {
                if ($i > 0) {
                    $bccs[] = $convenioBccs[$i]->getEmail();
                }
            }
        } else {
            $bccs = $convenioBccs->map(function(Representante $representante) {
                return $representante->getEmail();
            })->toArray();
        }
        return $bccs;
    }

    public function enviar(Autoliquidacion $autoliquidacion, string $path, $recipients, $bcc = [])
    {
        $subject = 'Autoliquidación Empleados ' . $autoliquidacion->getPeriodo()->format('Y-m')
            . ' ' . $autoliquidacion->getConvenio()->getNombre();
        $recipients = is_array($recipients) ? $recipients : [$recipients];

        $body = '<p>Buen día</p><p>Adjuntamos los pdfs que certifican el pago de seguridad social de los empleados</p>';

        $file = $this->fileManagerService->file($path);
        if($file->getSize('MB') > $this->halconCorreo->adjuntoLimite()) {
            $file->upload($this->autoliqFileManager->exportPath(
                $file->getExtension() === 'zip' ? FileManager::DIR_EXPORT_ZIP : FileManager::DIR_EXPORT_PDF,
                $autoliquidacion->getPeriodo()
            ));
            $body = '<p>Buen día</p><p>En el siguiente link puede descargar los pdfs que certifican el pago de seguridad social de los empleados</p>'
                  . '<p><a href="'.$file->generateLink('+3 month').'" target="_blank">'.$subject.'</a></p>';
            $path = null;
        }

        $mensaje = $this->halconCorreo->mensaje()
            ->to(...$recipients)
            ->bcc(...$bcc)
            ->subject($subject)
            ->html($body . '<p>Feliz día</p>');
        if($path) {
            $mensaje->attachFromPath($path);
        }

        return $this->halconCorreo->enviar($mensaje);
    }

    private function getExportSize(Autoliquidacion $autoliquidacion, $extension)
    {
        $dir = $extension === 'zip' ? FileManager::DIR_EXPORT_ZIP : FileManager::DIR_EXPORT_PDF;
        return $this->autoliqFileManager->getFileSize(
            $autoliquidacion->getPeriodo(),
            $autoliquidacion->getConvenio()->getCodigo(),
            $dir, 'MB');
    }

}