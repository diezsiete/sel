<?php


namespace App\Service;


use App\Form\Model\ContactoModel;
use Psr\Container\ContainerInterface;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;

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
     * @var SelParameters
     */
    private $parameters;

    public function __construct(Swift_Mailer $mailer, ContainerInterface $container, SelParameters $parameters)
    {
        $this->mailer = $mailer;
        $this->container = $container;
        $this->parameters = $parameters;
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
        $contactoEmail = $this->parameters->getContactoEmail();
        $this->send($this->parameters->getRazon() . '. Pagina web formulario contacto', $contacto->email, $contactoEmail,
            'emails/contacto.html.twig', ['contacto' => $contacto]);
    }
}