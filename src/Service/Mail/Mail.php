<?php


namespace App\Service\Mail;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mail
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Email
     */
    private $email;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function from($from)
    {
        $this->createEmail()->from($from);
        return $this;
    }

    public function to(...$to)
    {
        call_user_func_array([$this->createEmail(), 'to'], $to);
        return $this;
    }

    public function cc(...$addresses)
    {
        call_user_func_array([$this->createEmail(), 'cc'], $addresses);
        return $this;
    }

    public function bcc(...$addresses)
    {
        call_user_func_array([$this->createEmail(), 'bcc'], $addresses);
        return $this;
    }

    public function subject($subject)
    {
        $this->createEmail()->subject($subject);
        return $this;
    }

    public function text($body)
    {
        $this->createEmail()->text($body);
        return $this;
    }

    public function html($body)
    {
        $this->createEmail()->html($body);
        return $this;
    }

    public function attach($path)
    {
        $email = $this->createEmail();
        $email->attachFromPath($path);
        return $this;
    }

    public function send()
    {
        if($this->email) {
            $this->mailer->send($this->email);
            $this->clear();
        }
    }

    public function clear()
    {
        $this->email = null;
    }

    protected function createEmail(): Email
    {
        if(!$this->email) {
            $this->email = new Email();
        }
        return $this->email;
    }

}