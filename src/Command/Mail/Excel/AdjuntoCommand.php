<?php


namespace App\Command\Mail\Excel;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Email;

class AdjuntoCommand extends ExcelMasivoCommand
{
    protected static $defaultName = "sel:mail:excel:adjunto";
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $adjunto;

    /**
     * @param Filesystem $filesystem
     * @required
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    protected function configure()
    {
        parent::configure();
        $this->addArgument('adjunto', InputArgument::REQUIRED, 'Path absoluto al archivo a adjuntar');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $adjunto = $input->getArgument('adjunto');
        if($this->filesystem->exists($adjunto)) {
            $this->adjunto = $adjunto;
            parent::execute($input, $output);
        } else {
            $this->io->error("Adjunto '$adjunto' not found");
        }
    }   

    protected function buildEmail(string $to): Email
    {
        return parent::buildEmail($to)
            ->attachFromPath($this->adjunto);
    }
}