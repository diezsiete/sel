<?php

namespace App\Command\Mail;

use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\MailerCommand;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailImageCommand extends TraitableCommand
{
    use SearchByConvenioOrEmpleado,
        MailerCommand,
        SelCommandTrait,
        ConsoleProgressBar;

    protected $empleadosEmails = null;

    protected static $defaultName = "sel:mail:image";

    protected function configure()
    {
        parent::configure();
        $this->addOption('start-from', null, InputOption::VALUE_REQUIRED,
            'Empezar por el index Empezar por el index (por si falla)', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $from = 'direccion.comercial@servilabor.com.co';

        $emails = $this->getEmpleadosEmails($input);

        $startFrom = $input->getOption('start-from');
        $i = 0;
        foreach($emails as $email) {
            if($i >= $startFrom) {
                if($this->emailIsValid($email)) {
                    $this->mailer->send($this->buildEmail($from, $email));
                }
            }
            $this->progressBarAdvance();
            $i++;
        }

    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->getEmpleadosEmails($input));
    }

    private function getEmpleadosEmails(InputInterface $input)
    {
        if($this->empleadosEmails === null) {
            if($to = $input->getOption('to')) {
                $this->empleadosEmails  = $to;
            } else {
                $this->empleadosEmails  = $this->getEmpleados('email');
            }
        }
        return $this->empleadosEmails;
    }
}