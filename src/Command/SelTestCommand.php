<?php

namespace App\Command;

use App\Service\Novasoft\Report\Report\LiquidacionNominaReport;
use App\Service\Novasoft\Report\Report\TrabajadoresActivosReport;
use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SelTestCommand extends Command
{
    protected static $defaultName = 'sel:test';
    /**
     * @var LiquidacionNominaReport
     */
    private $report;
    /**
     * @var TrabajadoresActivosReport
     */
    private $trabajadoresActivosReport;

    public function __construct(LiquidacionNominaReport $report, TrabajadoresActivosReport $trabajadoresActivosReport)
    {
        parent::__construct();
        $this->report = $report;
        $this->trabajadoresActivosReport = $trabajadoresActivosReport;
    }

    protected function configure()
    {
        $this->addArgument('index', InputArgument::OPTIONAL);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

//        $index = $input->getArgument('index');
//        dump($this->report->getParameters()[$index]);
        $this->report
            ->setFechaInicial(DateTime::createFromFormat('Y-m-d', '2019-11-01'))
            ->setFechaFinal(DateTime::createFromFormat('Y-m-d', '2019-11-30'))
            ->setConvenio('ALD-SA')
            ->setIdentificacion(1095942504);
        try {
            $return = $this->report->renderMap();
            dump($return);
        }catch(Exception $e) {
            dump($e);
        }
    }
}
