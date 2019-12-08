<?php


namespace App\Command\Autoliquidacion;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Service\Scraper\AutoliquidacionScraper;
use App\Service\Scraper\ScraperMessenger;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AutoliquidacionDownloadCommand extends TraitableCommand
{
    use Loggable,
        PeriodoOption,
        SearchByConvenioOrEmpleado,
        ConsoleProgressBar,
        SelCommandTrait;

    protected static $defaultName = 'sel:autoliquidacion:download';

    /**
     * @var AutoliquidacionScraper
     */
    private $scraper;
    /**
     * @var ScraperMessenger
     */
    private $scraperMessenger;


    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return 0;
    }

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                AutoliquidacionScraper $scraper, ScraperMessenger $scraperMessenger)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->scraper = $scraper;
        $this->scraperMessenger = $scraperMessenger;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('overwrite', 'o', InputOption::VALUE_OPTIONAL,
            'Vuelve y descarga la autoliquidacion aun halla sido exitosa. 
            Si especifica codigo sobrescribe las que tengan ese codigo (ej: -o\!200)', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input);
        $autoliquidaciones = $this->getAutoliquidacionesEmpleado($periodo, $input->getOption('overwrite'));
        
        if($autoliquidaciones) {
            foreach($autoliquidaciones as $autoliquidacion) {
                $this->output->writeln($autoliquidacion->getEmpleado()->getUsuario()->getIdentificacion());
                $this->scraperMessenger->generateAutoliquidacion($autoliquidacion);
            }
        } else {
            $this->io->writeln("No hay autolquidaciones para descargar");
        }
    }

    /**
     * @param $periodo
     * @param bool $overwrite
     * @param bool $count
     * @return AutoliquidacionEmpleado[]
     */
    protected function getAutoliquidacionesEmpleado($periodo, $overwrite = false, $count = false)
    {
        $overwrite = $overwrite === false ? null : $overwrite;
        /** @var AutoliquidacionEmpleadoRepository $autliquidacionRepo */
        $autliquidacionRepo = $this->em->getRepository(AutoliquidacionEmpleado::class);

        if($this->isSearchConvenio() && $this->searchValue) {
            $autoliquidaciones = $autliquidacionRepo->findByConvenio($this->searchValue, $periodo, $overwrite);
        } else {
            $idents = $this->isSearchConvenio() ? [] : $this->getIdents();
            $autoliquidaciones = $autliquidacionRepo->findByIdentificaciones($periodo, $idents, $overwrite);
        }

        return $autoliquidaciones;
    }
}