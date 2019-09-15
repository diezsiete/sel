<?php /** @noinspection PhpComposerExtensionStubsInspection */


namespace App\Service\Autoliquidacion;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Convenio;
use App\Entity\Representante;
use App\Service\Configuracion\Configuracion;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use ZipArchive;

class Email
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Swift_Mailer $mailer, Configuracion $configuracion)
    {
        $this->mailer = $mailer;
        $this->configuracion = $configuracion;
    }

    /**
     * @param Convenio $convenio
     * @param array $overwrite
     * @return Representante[]|string[]
     */
    public function getRecipients(Convenio $convenio, $overwrite = [])
    {
        $recipients = $overwrite ? $overwrite : [];
        if(!$overwrite) {
            $encargados = $convenio->getEncargados();
            if (!$encargados->count()) {
                if ($bcc = $convenio->getBcc()->first()) {
                    $encargados[] = $bcc;
                }
            }
        }
        return $recipients;
    }

    public function getBccsEmails(Convenio $convenio, $overwrite = [], $merge = false)
    {
        $bccs = $overwrite ? $overwrite : [];
        if(!$overwrite || $merge) {
            $encargados = $convenio->getEncargados();
            $convenioBccs = $convenio->getBcc(true);
            if (!$encargados->count()) {
                for ($i = 0; $i < count($convenioBccs); $i++) {
                    if ($i > 0) {
                        $bccs[] = $convenioBccs[$i];
                    }
                }
            } else {
                $bccs = array_merge($bccs, $convenioBccs);
            }
        }
        return $bccs;
    }

    public function send(Autoliquidacion $autoliquidacion, string $path, $recipient, $bcc = [])
    {

        $failedRecipients = [];

        $message = new Swift_Message('Autoliquidación Empleados '
            . $autoliquidacion->getPeriodo()->format('Y-m')
            . ' ' . $autoliquidacion->getConvenio()->getNombre());
        $message->setBody(
            '<p>Buen día</p>
                   <p>Adjuntamos los pdfs del pago de seguridad social de los empleados</p><p>Feliz día</p>'
            , 'text/html');

        $message->attach(
            Swift_Attachment::fromPath($path)
                ->setFilename($autoliquidacion->getConvenio()->getCodigo() . '.zip')
        );

        $message->setFrom([$this->configuracion->getMail() => $this->configuracion->getRazon() . ' Portal Clientes']);
        $message->setTo($recipient);

        foreach ($bcc as $bccEmail) {
            $message->addBcc($bccEmail);
        }

        $this->mailer->send($message, $failedRecipients);

        return $failedRecipients;
    }
}