<?php


namespace App\Command\Helpers;



use App\Command\Helpers\ModifyRun\Event\ModifyRunAfterEvent;
use App\Command\Helpers\ModifyRun\Event\ModifyRunBeforeEvent;
use App\Command\Helpers\ModifyRun\Annotation\ModifyRunAfter;
use App\Command\Helpers\ModifyRun\Annotation\ModifyRunBefore;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


trait ConsoleProgressBar
{
    /**
     * @var ProgressBar
     */
    protected $progressBar = null;

    protected $progressBarFormat = 'debug';


    protected function progressBarAdvance()
    {
        if($this->progressBar) {
            $this->progressBar->advance();
        }
    }

    /**
     * @ModifyRunBefore
     */
    public function initProgressBar(ModifyRunBeforeEvent $event)
    {
        $countProgressBar = $this->progressBarCount($event->input, $event->output);
        if($countProgressBar) {
            $this->progressBar = new ProgressBar($event->output, $countProgressBar);
            $this->progressBar->setFormat($this->progressBarFormat);
            return $this->progressBar;
        }
    }

    /**
     * @ModifyRunAfter
     */
    public function endProgressBar(ModifyRunAfterEvent $event)
    {
        if($this->progressBar) {
            $this->progressBar->finish();
            $event->output->writeln('');
        }
    }

    abstract protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int;

    /**
     * Obliga a utilizar el trait ModifyRun
     */
    abstract protected function modifyRun();
}