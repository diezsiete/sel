<?php


namespace App\Command\Mail;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Service\Excel\Factory;
use App\Service\Utils;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ExcelMasivoCommand extends TraitableCommand
{
    protected static $defaultName = "sel:mailer:excel-masivo";
    private $kernelProjectDir;

    /**
     * @var Factory
     */
    private $excelFactory;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Utils
     */
    private $utils;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, $kernelProjectDir,
                                Factory $excelFactory, MailerInterface $mailer, Utils $utils)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->kernelProjectDir = $kernelProjectDir;
        $this->excelFactory = $excelFactory;
        $this->mailer = $mailer;
        $this->utils = $utils;
    }

    protected function configure()
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED)
            ->addArgument('col', InputArgument::REQUIRED, 'La columna con los correos')
            ->addArgument('img', InputArgument::REQUIRED, 'Si el correo es una sola imagen')
            ->addOption('delimiters', 'd', InputOption::VALUE_IS_ARRAY|InputOption::VALUE_REQUIRED,
                'Delimitador en celda con multiples correos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $this->kernelProjectDir . "/" . $input->getArgument('file');
        $col = $input->getArgument('col');
        $img = $this->kernelProjectDir . "/" . $input->getArgument('img');
        $delimiter = $input->getOption('delimiters');

        $emails = $this->obtainEmails($filePath, $col, $delimiter);
        // dd($emails);

        foreach($emails as $email) {
            if($this->utils->emailIsValid($email)) {
                $this->sendImage($email, $img);
                $this->io->success($email);
            } else {
                $this->io->error($email);
            }
        }

        // $this->sendImage('guerrerojosedario@gmail.com', $img);
    }

    private function obtainEmails($filePath, $col, $delimiters)
    {
        $read = $this->excelFactory->read($filePath);
        $read->enableTrim("\xA0\xC2");
        $col = $read->col($col);

        $emails = [];

        foreach ($col as $cellValue) {
            $explode = null;
            if($delimiters){
                foreach($delimiters as $delimiter) {
                    $explode = $this->explodeBy($delimiter, $cellValue);
                    if($explode) {
                        break;
                    }
                }
            }
            if($explode) {
                $emails = array_merge($emails, $explode);
            } else {
                $emails[] = $cellValue;
            }
        }

        return $emails;
    }

    private function explodeBy($delimiter, $value)
    {
        $explode = explode($delimiter, $value);
        if(count($explode) > 1) {
            $explode = array_map('trim', $explode);
            return $explode;
        }
        return null;
    }

    private function sendImage($to, $image)
    {
        $email = (new Email())
            ->embedFromPath($image, 'img')
            ->from('direccion.comercial@servilabor.com.co')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Campaña fin de año!')
            ->html('<img src="cid:img">');

        $this->mailer->send($email);
    }


}