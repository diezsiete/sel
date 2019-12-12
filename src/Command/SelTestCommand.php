<?php

namespace App\Command;

use App\Service\Novasoft\Report\Report\TrabajadoresActivosReport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SelTestCommand extends Command
{
    protected static $defaultName = 'sel:test';
    /**
     * @var TrabajadoresActivosReport
     */
    private $report;

    public function __construct(TrabajadoresActivosReport $report)
    {
        parent::__construct();
        $this->report = $report;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->report->setConvenio('ALD-SA');

        //$return = $this->report->renderAssociative();

        $map = $this->report->renderMap();
        foreach($map as $item) {
            if(!$item->getCentroCosto()) {
                dump($item);
            }
        }
    }
}
