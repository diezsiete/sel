<?php


namespace App\Command\Scraper;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Service\Exec;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class KillAutoConsumeCommand extends TraitableCommand
{
    protected static $defaultName = 'sel:scraper:kill-auto-consume';
    /**
     * @var Exec
     */
    private $exec;


    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, Exec $exec)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->exec = $exec;
    }

    protected function configure()
    {
        $this
            ->setDescription('Matar proceso de auto consumo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if($this->exec->uniqueExists('messenger')) {
            $this->exec->killUnique('messenger');
            $io->success('auto consumo matado exitosamente');
        } else {
            $io->warning('no existe proceso de auto consumo');
        }

    }
}