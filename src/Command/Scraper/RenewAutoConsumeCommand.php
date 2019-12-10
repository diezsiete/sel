<?php


namespace App\Command\Scraper;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\Scraper\MessageHvRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Exec;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RenewAutoConsumeCommand extends TraitableCommand
{
    protected static $defaultName = 'sel:scraper:renew-auto-consume';
    /**
     * @var Exec
     */
    private $exec;
    /**
     * @var MessageHvRepository
     */
    private $messageHvRepository;
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                Exec $exec, MessageHvRepository $messageHvRepository, Configuracion $configuracion)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->exec = $exec;
        $this->messageHvRepository = $messageHvRepository;
        $this->configuracion = $configuracion;
    }

    protected function configure()
    {
        $this
            ->setDescription('Renueva proceso de auto consumo si hay en cola y mata el actual')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($this->exec->uniqueExists('messenger')) {
            $this->exec->deleteUniqueFiles('messenger');
        }
        if($this->messageHvRepository->queueHasMessages()) {
            $command = $this->configuracion->getScraper()->getConsumeCommand();
            $this->exec->asyncUnique($command, 'messenger');
        }
    }
}