<?php

namespace App\Command\ServicioEmpleados\ReportCache;


use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Command\Helpers\ServicioEmpleados\ReportCache as ReportCacheTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
// Necesario por bug de ReportCacheTrait utiliza trait ConnectToLogEvent que necesita esta y no es importada automaticamente
use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;

class ImportCommand extends TraitableCommand
{
    use SelCommandTrait,
        ReportCacheTrait;

    protected static $defaultName = "sel:se:report-cache:import";

    protected function configure()
    {
        parent::configure();
        $this->addOption('hard', null, InputOption::VALUE_NONE, 'Borra todo e importa todo')
            ->addOption('ignore-refresh-interval', null, InputOption::VALUE_NONE, 'Ignora el refresh interval');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $this->getSource($input);
        $hard = $input->getOption('hard');
        $ignoreRefreshInterval = $input->getOption('ignore-refresh-interval');


        foreach($this->findUsuario($input) as $usuario) {
            foreach($this->getReports($input) as $report) {
                if($hard) {
                    $this->reportCacheHandler->delete($usuario, $report, $source);
                }
                if($source === 'novasoft') {
                    $this->reportCacheHandler->handleNovasoft($usuario, $report, $ignoreRefreshInterval);
                } else {
                    $this->reportCacheHandler->handleHalcon($usuario, $report);
                }
            }
        }
    }
}