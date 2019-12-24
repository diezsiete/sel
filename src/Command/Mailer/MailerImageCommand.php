<?php

namespace App\Command\Mailer;

use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\MailerCommand;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailerImageCommand extends TraitableCommand
{
    use SearchByConvenioOrEmpleado,
        MailerCommand,
        SelCommandTrait,
        ConsoleProgressBar;

    protected $empleadosEmails = null;

    protected static $defaultName = "sel:mailer:image";

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $from = 'direccion.comercial@servilabor.com.co';
        $emails = $this->getEmpleadosEmails();
        foreach($emails as $email) {
            if($this->emailIsValid($email)) {
                $this->mailer->send($this->buildEmail($from, $email));
            }
            $this->progressBarAdvance();
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->getEmpleadosEmails());
    }

    private function getEmpleadosEmails()
    {
        if($this->empleadosEmails === null) {
            $this->empleadosEmails = $this->getEmpleados('email');
        }
        return $this->empleadosEmails;
    }
}