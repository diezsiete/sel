<?php


namespace App\Command\Migration\Halcon;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\Halcon\VinculacionRepository;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NominaCommand extends TraitableCommand
{
    protected static $defaultName = "sel:migration:halcon:nomina";
    /**
     * @var VinculacionRepository
     */
    private $vinculacionRepo;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                VinculacionRepository $vinculacionRepo)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->vinculacionRepo = $vinculacionRepo;
    }

    protected function configure()
    {
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ident = 3094055;
        $comprobantes = $this->vinculacionRepo->findComprobantesByIdent($ident);
        dump($comprobantes);
    }
}