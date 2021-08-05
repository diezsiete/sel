<?php


namespace App\Service;


use App\Entity\Main\RestaurarClave;
use App\Entity\Main\SolicitudServicio;
use App\Form\Model\ContactoModel;
use App\Service\Configuracion\Configuracion;
use App\Service\Halcon\Servicios\Correo;
use Psr\Container\ContainerInterface;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;

/**
 * Class Mailer
 * @package App\Service
 * @deprecated
 */
class Mailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var Correo
     */
    private $correo;

    public function __construct(Swift_Mailer $mailer, ContainerInterface $container, Configuracion $configuracion, Correo $correo)
    {
        $this->mailer = $mailer;
        $this->container = $container;
        $this->configuracion = $configuracion;
        $this->correo = $correo;
    }

    public function send($subject, $from, $to, $view, $parameters, $attachmentPath = false)
    {
        $body = $this->container->get('twig')->render($view, $parameters);

        $message = (new Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html');

        if($attachmentPath) {
            $message->attach(Swift_Attachment::fromPath($attachmentPath));
        }

        $this->mailer->send($message);
    }

    public function sendContacto(ContactoModel $contacto)
    {
        $this->send($this->configuracion->getRazon() . '. Pagina web formulario contacto', $contacto->from, $contacto->to,
            'emails/contacto.html.twig', ['contacto' => $contacto]);
    }

    public function sendSolicitudServicio(SolicitudServicio $solicitudServicio)
    {
        $emails = $this->configuracion->getEmails()->getContacto();
        if(isset($emails['Solicitud servicios'])) {
            $emails = $emails['Solicitud servicios'];
            foreach($emails as $email) {
                $this->send($this->configuracion->getRazon() . '. Solicitud servicio', $solicitudServicio->getEmailCorporativo(), $email,
                    'emails/solicitud-servicio.html.twig', [
                        'solicitudServicio' => $solicitudServicio,
                        'tipoServicio' => SolicitudServicio::SERVICIO_TYPE[$solicitudServicio->getServicio()]
                    ]);
            }
        }

    }

    public function sendOlvido(RestaurarClave $restaurarClave)
    {
        try {
            $mensaje = $this->correo->mensaje()
                ->subject($this->configuracion->getRazon() . '. Pagina web restaurar clave')
                ->to($restaurarClave->getUsuario()->getEmail())
                ->html($this->container->get('twig')->render('emails/olvido.html.twig', [
                    'restaurarClave' => $restaurarClave
                ]));
            $this->correo->enviar($mensaje);
        } catch (\Exception $e) {
            // TODO mandar mail a admin o algo
        }

        // $this->send(
        //     $this->configuracion->getRazon() . '. Pagina web restaurar clave',
        //     $this->configuracion->getMail(),
        //     $restaurarClave->getUsuario()->getEmail(),
        //     'emails/olvido.html.twig', [
        //         'restaurarClave' => $restaurarClave
        //     ]);
    }
}