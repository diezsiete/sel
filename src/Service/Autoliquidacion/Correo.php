<?php


namespace App\Service\Autoliquidacion;

use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Main\Convenio;
use App\Entity\Main\Representante;
use \App\Service\Halcon\Servicios\Correo as HalconCorreo;


class Correo
{
    /**
     * @var HalconCorreo
     */
    private $halconCorreo;

    public function __construct(HalconCorreo $halconCorreo)
    {
        $this->halconCorreo = $halconCorreo;
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

        //puede ser .zip o .pdf
        $extension = preg_replace('/(.*)(\.\w+)$/', '$2', $path);

        $recipients = is_array($recipients) ? $recipients : [$recipients];
        $mensaje = $this->halconCorreo->mensaje()
            ->to(...$recipients)
            ->bcc(...$bcc)
            ->subject($subject)
            ->html('<p>Buen día</p><p>Adjuntamos los pdfs del pago de seguridad social de los empleados</p><p>Feliz día</p>')
            ->attachFromPath($path);


        return $this->halconCorreo->enviar($mensaje);
    }
}