<?php


namespace App\Command\Info;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\Main\ConvenioRepository;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConvenioCommand extends TraitableCommand
{
    protected static $defaultName = "sel:info:convenio";
    /**
     * @var \App\Repository\Main\ConvenioRepository
     */
    private $convenioRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ConvenioRepository $convenioRepository)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->convenioRepository = $convenioRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('search', InputArgument::OPTIONAL, 'Termino para filtrar resultado. Ignorado muestra todos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $search = $input->getArgument('search');

        if($search) {
            $convenios = $this->convenioRepository->findByCodigoOrNombre($search);
        } else {
            $convenios = $this->convenioRepository->findAll();
        }

        if($convenios) {
            $table = new Table($output);
            $table->setHeaders(['codigo', 'nombre', 'ssrs_db']);
            foreach($convenios as $convenio) {
                $table->addRow([$convenio->getCodigo(), $convenio->getNombre(), $convenio->getSsrsDb()]);
            }
            $table->render();
        } else {
            $this->io->warning("no hay convenios");
        }
    }
}