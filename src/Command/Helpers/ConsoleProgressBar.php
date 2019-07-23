<?php


namespace App\Command\Helpers;




use App\Command\Helpers\TraitableCommand\Annotation\AfterRun;
use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;
use App\Command\Helpers\TraitableCommand\Event\AfterRunEvent;
use App\Command\Helpers\TraitableCommand\Event\BeforeRunEvent;
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
     * @BeforeRun
     */
    public function initProgressBar(BeforeRunEvent $event)
    {
        $countProgressBar = $this->progressBarCount($event->getInput(), $event->getOutput());
        if($countProgressBar) {
            $this->progressBar = new ProgressBar($event->getOutput(), $countProgressBar);
            $this->progressBar->setFormat($this->progressBarFormat);
            return $this->progressBar;
        }
        return null;
    }

    /**
     * @AfterRun
     */
    public function endProgressBar(AfterRunEvent $event)
    {
        if($this->progressBar) {
            $this->progressBar->finish();
            $event->getOutput()->writeln('');
        }
    }

    abstract protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int;

}