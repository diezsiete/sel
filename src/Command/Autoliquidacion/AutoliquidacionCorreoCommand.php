<?php


namespace App\Command\Autoliquidacion;


use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Main\Convenio;
use App\Entity\Main\Representante;
use App\Service\Autoliquidacion\Email;
use App\Service\Autoliquidacion\ExportPdf;
use App\Service\Autoliquidacion\ExportZip;
use App\Service\Autoliquidacion\AutoliqCorreoService;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AutoliquidacionCorreoCommand extends TraitableCommand
{
    use SelCommandTrait,
        Loggable,
        PeriodoOption {
            getPeriodo as traitGetPeriodo;
        }

    public static $defaultName = "sel:autoliquidacion:correo";

    /**
     * @var Email
     */
    private $email;
    /**
     * @var ExportZip
     */
    private $exportZip;
    /**
     * @var ExportPdf
     */
    private $exportPdf;
    /**
     * @var AutoliqCorreoService
     */
    private $correoService;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, Email $email,
                                ExportZip $export, ExportPdf $exportPdf, AutoliqCorreoService $correo)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->email = $email;
        $this->exportZip = $export;
        $this->exportPdf = $exportPdf;
        $this->correoService = $correo;
    }

    protected function configure()
    {
        parent::configure();
        $this->periodoDescription = 'periodo que se va a enviar email en formato Y-m (si no se envia se toma basado en la fecha actual)';

        $this->setDescription("Enviar correo de autoliquidaciones")
            ->addArgument('convenios', InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'convenios codigos para enviar (si no se elige se envia a todos los disponibles)' )
            ->addOption('to', null, InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'para pruebas. Todos los correos se envian a esta direccion')
            ->addOption('bcc', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'para pruebas. Todos los correos bcc se envian a esta direccion')
            ->addOption('bcc-merge', null, InputOption::VALUE_NONE,
                'Se utilizan los bcc originales y los correos de la opcion bcc se agregan')
            ->addOption('force', 'f', InputOption::VALUE_NONE,
                'al especificar, envia los correos sin importar el estado de la autoliquidacion')
            ->addOption('dont-send', null, InputOption::VALUE_NONE,
                'No envia correo')
            ->addOption('sended-not', null, InputOption::VALUE_NONE,
                'Solo toma las liquidaciones a las que no se les halla enviado correo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $testRecipient = $input->getOption('to') ? $input->getOption('to') : [];
        $testBcc = $input->getOption('bcc');
        $bccMerge = $input->getOption('bcc-merge');
        $force = $input->getOption('force');
        $sendedNot = $input->getOption('sended-not');

        $autoliqs = $this->em->getRepository(Autoliquidacion::class)
            ->findByConvenio($input->getArgument('convenios'), $this->getPeriodo($input));

        foreach($autoliqs as $autoliq) {
            $porcentajeEjecucion = $autoliq->getPorcentajeEjecucion();

            $this->logConvenio($autoliq->getConvenio(), $porcentajeEjecucion);

            $send = ($force || (!$force && $porcentajeEjecucion === 100)) && (!$sendedNot || !$autoliq->isEmailSended());
            if($send) {
                $recipients = $testRecipient ?: $this->correoService->getRecipients($autoliq);
                $bccs = $testRecipient || ($testBcc && !$bccMerge) ? $testBcc : $this->correoService->getBccsEmails($autoliq);
                if(!$testRecipient && $testBcc && $bccMerge) {
                    $bccs = array_merge($bccs, $testBcc);
                }

                foreach($recipients as $recipient) {
                    $this->logRecipients($recipient, $bccs);
                    try {
                        $filePath = $this->generateExportFile($autoliq, $recipient);

                        if($filePath && !$input->getOption('dont-send')) {
                            $recipientEmail = is_object($recipient) ? $recipient->getEmail() : $recipient;

                            $this->correoService->enviar($autoliq, $filePath, $recipientEmail, $bccs);
                            $this->info('Exito envio de email');
                            if(!$testRecipient) {
                                $autoliq->setEmailSended(1);
                                $this->em->flush();
                            }
                        }
                    } catch(Exception $e) {
                        $this->error($e->getMessage());
                    }
                }
            }
        }
        return 0;
    }

    public function getPeriodo(InputInterface $input)
    {
        $this->periodo = $this->traitGetPeriodo($input, false);
        if(!$this->periodo) {
            $this->periodo = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m') . '-01');
        }
        return $this->periodo;
    }

    private function generateExportFile(Autoliquidacion $autoliq, $recipient)
    {
        $export = $this->exportZip;

        //fix temp
        //TODO permitir que esto sea via configuracion
        $codigo = $autoliq->getConvenio()->getCodigo();
        if(in_array($codigo, ['GEOCOL', 'STECOL'])) {
            $export = $this->exportPdf;
        }

        $path = $export->generate($autoliq, is_object($recipient) ? $recipient : null);
        $size = $export->getSize($autoliq);

        $this->logFile($path, $size);

        return $path;
    }

    protected function getConvenios($codigos = [])
    {
        $convenioRepo = $this->em->getRepository(Convenio::class);
        return $codigos ?
            $convenioRepo->findBy(['codigo' => $codigos]) :
            $convenioRepo->findAll();
    }

    private function logConvenio(Convenio $convenio, $porcentajeEjecucion)
    {
        $this->info(sprintf('%s %s [%d]', $convenio->getCodigo(), $convenio->getNombre(), $porcentajeEjecucion));
    }

    /**
     * @param string|Representante $recipient
     */
    private function logRecipients($recipient, $bccs = [])
    {
        $this->info(sprintf('    %s', 'Encargado : ' . (is_object($recipient)
            ? "{$recipient->getUsuario()->getNombreCompleto()} [{$recipient->getEmail()}]"
            : (string)($recipient)
        )));
        $this->info('    bcss: ' . (!$bccs ? 'no' : ''));
        foreach($bccs as $bcc){
            $this->info('      ' . $bcc);
        }
    }

    private function logFile($path, $size)
    {
        $this->info('    Archivo [exito] : ' . $path);
        $this->info('    Archivo [size MB] : ' . $size);
    }
}