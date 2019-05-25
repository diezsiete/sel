<?php

namespace App\Command;

use App\Service\NovasoftImport\ConvenioImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SeConvenioActualizarCommand extends Command
{
    protected static $defaultName = 'se:convenio-actualizar';
    /**
     * @var ConvenioImport
     */
    private $convenioImport;

    public function __construct(ConvenioImport $convenioImport)
    {
        parent::__construct();
        $this->convenioImport = $convenioImport;
    }

    protected function configure()
    {
        $this->setDescription('Actualizar convenios desde novasoft');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $this->convenioImport->import();
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
