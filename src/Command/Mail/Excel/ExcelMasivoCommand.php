<?php


namespace App\Command\Mail\Excel;


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
    protected static $defaultName = "sel:mail:excel";

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

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                Factory $excelFactory, MailerInterface $mailer, Utils $utils)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->excelFactory = $excelFactory;
        $this->mailer = $mailer;
        $this->utils = $utils;
    }

    protected function configure()
    {
        $this
            ->addArgument('excel', InputArgument::REQUIRED, 'Path absoluto al archivo excel')
            ->addArgument('col', InputArgument::REQUIRED, 'La columna con los correos (eg "A")')
            ->addOption('subject', 's', InputOption::VALUE_REQUIRED, 'Subject del coreo')
            ->addOption('delimiters', 'd', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Delimitador en celda con multiples correos')
            ->addOption('html', null, InputOption::VALUE_OPTIONAL, 'Html opcional como body del correo')
            ->addOption('text', 't', InputOption::VALUE_OPTIONAL, 'Texto opcional como body del correo')
            ->addOption('prueba', 'p', InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY,
                'Utilizar para probar, no envia correo, excepto t sea un correo', []);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $emails = $this->obtainEmails($input);
        $prueba = $this->getPruebaOption($input);

        foreach($emails as $email) {
            if($this->utils->emailIsValid($email)) {
                if(!$prueba || is_array($prueba)) {
                    $emailObject = $this->buildEmail($email);
                    $this->mailer->send($emailObject);
                }
                $this->io->success($email);
            } else {
                $this->io->error($email);
            }
        }
    }

    protected function obtainEmails(InputInterface $input) {
        $prueba = $this->getPruebaOption($input);
        return is_array($prueba) ? $prueba : $this->readEmailsFromExcel(
            $input->getArgument('excel'),
            $input->getArgument('col'),
            $input->getOption('delimiters')
        );
    }

    protected function readEmailsFromExcel($filePath, $col, $delimiters)
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
                if($cellValue) {
                    $emails[] = $cellValue;
                }
            }
        }
        return $emails;
    }

    protected function explodeBy($delimiter, $value)
    {
        $explode = explode($delimiter, $value);
        if(count($explode) > 1) {
            $explode = array_map('trim', $explode);
            return $explode;
        }
        return null;
    }

    protected function getPruebaOption(InputInterface $input)
    {
        $prueba = !!$input->getOption('prueba') ? $input->getOption('prueba') : false;
        if($prueba && $prueba[0] === null) {
            $prueba = true;
        }
        return $prueba;
    }

    protected function getSubject()
    {
        return $this->input->getOption('subject') ?? "";
    }

    protected function getFrom()
    {
        return 'direccion.comercial@servilabor.com.co';
    }

    protected function getText()
    {
        return $this->input->getOption('text') ?? "";
    }

    protected function getHtml()
    {
        return $this->input->getOption('html') ?? "";
    }

    protected function buildEmail(string $to): Email
    {
        $email = (new Email())
                ->from($this->getFrom())
                ->to($to)
                ->subject($this->getSubject());
        if($html = $this->getHtml()) {
            $email->html($html);
        } else {
            $email->text($this->getText());
        }
        return $email;
    }
}