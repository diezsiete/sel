<?php


namespace App\Command\NovasoftImport;


use App\Command\Helpers\Loggable;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use Exception;
use SSRS\SSRSReportException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class NiCommand extends TraitableCommand
{
    use SelCommandTrait,
        Loggable;

    public function run(InputInterface $input, OutputInterface $output)
    {
        $return = 1;
        try {
            $return = parent::run($input, $output);
        }
        catch (SSRSReportException $e) {
            $this->error($e->errorDescription);
        }
        catch(Exception $e) {
            $this->error($e);
        }
        return $return;
    }
}