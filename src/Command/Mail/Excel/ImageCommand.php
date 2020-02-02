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

abstract class ImageCommand extends ExcelMasivoCommand
{
    protected static $defaultName = "sel:mail:excel:image";
    /**
     * @var
     */
    private $kernelProjectDir;
    /**
     * @var string
     */
    private $img;

    /**
     * @param $kernelProjectDir
     * @required
     */
    public function setKernelProjectDir($kernelProjectDir)
    {
        $this->kernelProjectDir = $kernelProjectDir;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('img', InputArgument::REQUIRED, 'Si el correo es una sola imagen');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->img = $this->kernelProjectDir . "/" . $input->getArgument('img');
        parent::execute($input, $output);
    }

    protected function buildEmail(string $to): Email
    {
        return (new Email())
            ->embedFromPath($this->img, 'img')
            ->from($this->getFrom())
            ->to($to)
            ->subject($this->getSubject())
            ->html('<img src="cid:img">');
    }


}