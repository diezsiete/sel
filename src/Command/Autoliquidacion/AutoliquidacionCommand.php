<?php


namespace App\Command\Autoliquidacion;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\ModifyRun\Loggable;
use App\Command\Helpers\ModifyRun\ModifyRun;
use App\Command\Helpers\OptionRequired;
use App\Command\Helpers\SearchByConvenioOrIdent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;



abstract class AutoliquidacionCommand extends Command
{
    use ModifyRun,
        Loggable,
        OptionRequired,
        SearchByConvenioOrIdent,
        ConsoleProgressBar;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EntityManagerInterface
     */
    protected $em;


    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @required
     */
    public function setEm(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function configure()
    {
        parent::configure();
    }

}