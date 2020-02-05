<?php


namespace App\Command\Mail;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Service\Excel\Factory;
use App\Service\Utils;
use Doctrine\Common\Annotations\Reader;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
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
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                Factory $excelFactory, MailerInterface $mailer, Utils $utils, Filesystem $filesystem)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->excelFactory = $excelFactory;
        $this->mailer = $mailer;
        $this->utils = $utils;
        $this->filesystem = $filesystem;
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
                'Utilizar para probar, no envia correo, excepto p sea un correo', [])
            ->addOption('adjunto', 'a', InputOption::VALUE_IS_ARRAY| InputOption::VALUE_REQUIRED, 'Path absoluto al archivo a adjuntar')
            ->addOption('img', 'i', InputOption::VALUE_IS_ARRAY| InputOption::VALUE_REQUIRED, 'Si el correo es una sola imagen, path a la imagen');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $emails = $this->obtainEmails($input);
        $prueba = $this->getPruebaOption($input);
        $adjuntos = $this->getAdjuntos($input);
        $imgs = $this->getImgs($input);

        foreach($emails as $email) {
            if($this->utils->emailIsValid($email)) {
                if(!$prueba || is_array($prueba)) {
                    $emailObject = $this->buildEmail($email, $adjuntos, $imgs);
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

    protected function buildEmail(string $to, $adjuntos = [], $img = []): Email
    {
        $email = (new Email())
                ->from($this->getFrom())
                ->to($to)
                ->subject($this->getSubject());
        if($img) {
            $html = "";
            for($i = 1; $i <= count($img); $i++) {
                $email->embedFromPath($img[$i - 1], 'img' . $i);
                $html .= '<img src="cid:img'.$i.'">';
            }
            $email->html($html);
        } else {
            if ($html = $this->getHtml()) {
                $email->html($html);
            } else {
                $email->text($this->getText());
            }
        }
        if($adjuntos) {
            foreach($adjuntos as $adjunto) {
                $email->attachFromPath($adjunto);
            }
        }
        return $email;
    }

    protected function getAdjuntos(InputInterface $input)
    {
        $adjuntos = $input->getOption('adjunto');
        foreach($adjuntos as $adjunto) {
            if(!$this->filesystem->exists($adjunto)) {
                throw new Exception("Adjunto '$adjunto' not found");
            }
        }
        return $adjuntos;
    }

    protected function getImgs(InputInterface $input)
    {
        $imgs = $input->getOption('img');
        foreach($imgs as $img) {
            if (!$this->filesystem->exists($img)) {
                throw new Exception("Imagen '$img' not found");
            }
        }
        return $imgs;
    }
}