<?php

namespace App\Command\Helpers;

use Symfony\Component\Console\Input\InputOption;
use App\Command\Helpers\TraitableCommand\Annotation\Configure;

trait TestOption
{
    protected $testDescription = 'Pruebas, solo muestra info';

    /**
     * @Configure
     */
    public function addOptionTest()
    {
        $this->addOption('test', 't', InputOption::VALUE_NONE, $this->testDescription);
    }

    protected function isTest()
    {
        return (bool)$this->input->getOption('test');
    }
}