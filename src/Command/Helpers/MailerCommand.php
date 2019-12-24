<?php


namespace App\Command\Helpers;


use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use ReflectionClass;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Mime\Email;

trait MailerCommand
{
    protected $bccOptionDescription = "Agregar bcc";
    /**
     * @Configure
     */
    public function addOptionPeriodo()
    {
        $this
            ->addOption('cc', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "Copia de email")
            ->addOption('bcc', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, $this->bccOptionDescription)
            ->addOption('reply-to', null,  InputOption::VALUE_REQUIRED, "Reply to email")
            ->addOption('priority', null, InputOption::VALUE_REQUIRED,
                "[" . print_r((new ReflectionClass(Email::class))->getConstants(), 1) . "]")
        ;
    }

    protected function sendImage($from, $to, $image, $subject)
    {
        $email = (new Email())
            ->embedFromPath($image, 'img')
            ->from($from)
            ->to($to);
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->subject($subject)
            //->html('<img src="cid:img">');

        $this->mailer->send($email);
    }
}