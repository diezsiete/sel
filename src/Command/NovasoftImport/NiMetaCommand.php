<?php


namespace App\Command\NovasoftImport;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NiMetaCommand extends Command
{
    public static $defaultName = "sel:ni:meta";

    protected function configure()
    {
        $this->setDescription("Para importar convenio empleados y nomina via cron");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->find('sel:ni:convenio')->run($input, $output);

        $this->getApplication()->find('sel:ni:empleado')->run($input, $output);

        /*$this->getApplication()->find('sel:ni:nomina')
            ->run(new ArrayInput([
                'command' => 'sel:ni:nomina',
                '-p' => null
            ]), $output);*/
    }
}