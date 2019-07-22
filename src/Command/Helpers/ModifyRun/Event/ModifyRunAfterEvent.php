<?php


namespace App\Command\Helpers\ModifyRun\Event;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ModifyRunAfterEvent extends Event
{
    /**
     * @var InputInterface
     */
    public $input;
    /**
     * @var OutputInterface
     */
    public $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {

        $this->input = $input;
        $this->output = $output;
    }
}