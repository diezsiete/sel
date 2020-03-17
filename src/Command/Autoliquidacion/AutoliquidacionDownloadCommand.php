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
use App\Entity\Autoliquidacion\AutoliquidacionProgreso;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionProgresoRepository;
use App\Service\Ael\AelClient;
use App\Service\Autoliquidacion\FileManager;
use App\Service\Scraper\AutoliquidacionScraper;
use App\Service\Scraper\Exception\ScraperClientException;
use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Exception\ScraperTimeoutException;
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
     * @var AutoliquidacionProgresoRepository
     */
    private $autoliquidacionProgresoRepository;

    private $autoliquidacionesEmpleado = null;
    /**
     * @var AelClient
     */
    private $aelClient;
    /**
     * @var FileManager
     */
    private $autoliquidacionService;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                AutoliquidacionProgresoRepository $autoliquidacionProgresoRepository,
                                AelClient $aelClient, FileManager $autoliquidacionService)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->autoliquidacionProgresoRepository = $autoliquidacionProgresoRepository;
        $this->aelClient = $aelClient;
        $this->autoliquidacionService = $autoliquidacionService;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('overwrite', 'o', InputOption::VALUE_OPTIONAL,
            'Vuelve y descarga la autoliquidacion aun halla sido exitosa. 
            Si especifica codigo sobrescribe las que tengan ese codigo (ej: -o\!200)', false);
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->getAutoliquidacionesEmpleado($this->getPeriodo($input), $input->getOption('overwrite')));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input);

        $autoliquidaciones = $this->getAutoliquidacionesEmpleado($periodo, $input->getOption('overwrite'));

        if($autoliquidaciones) {
//            $autoliquidacionProgreso = (new AutoliquidacionProgreso())->setTotal(count($autoliquidaciones));
//            $this->em->persist($autoliquidacionProgreso);
//            $this->em->flush();
            foreach($autoliquidaciones as $autoliquidacion) {
                if($output->isVeryVerbose()) {
                    $this->output->writeln($autoliquidacion->getEmpleado()->getUsuario()->getIdentificacion());
                }
                try {
                    $ident = $autoliquidacion->getUsuario()->getIdentificacion();

                    $this->aelClient->certificadoDownload($ident, $periodo);
                    $resource = $this->aelClient->pdfDownload($ident, $periodo);
                    $this->autoliquidacionService->uploadPdfResource($periodo, $ident, $resource);
                    $this->aelClient->pdfDelete($ident, $periodo);

                    $autoliquidacion->setExito(true)->setCode(200);
//                    $autoliquidacionProgreso->setLastMessage($message);
                } catch (\Exception $e) {
                    $autoliquidacion
                        ->setExito($e->getCode() === 404)
                        ->setSalida($e->getMessage())
                        ->setCode($e->getCode());
                }
                $autoliquidacion->getAutoliquidacion()->calcularPorcentajeEjecucion();
                $this->em->flush();
                $this->progressBarAdvance();
            }
        } else {
            $this->io->writeln('No hay autolquidaciones para descargar');
        }
    }

    /**
     * @param $periodo
     * @param bool $overwrite
     * @return AutoliquidacionEmpleado[]
     */
    protected function getAutoliquidacionesEmpleado($periodo, $overwrite = false)
    {
        if($this->autoliquidacionesEmpleado === null) {

            $overwrite = $overwrite === false ? null : $overwrite;
            /** @var AutoliquidacionEmpleadoRepository $autliquidacionRepo */
            $autliquidacionRepo = $this->em->getRepository(AutoliquidacionEmpleado::class);

            if ($this->isSearchConvenio() && $this->searchValue) {
                $autoliquidaciones = $autliquidacionRepo->findByConvenio($this->searchValue, $periodo, $overwrite);
            }
            else {
                $idents = $this->isSearchConvenio() ? [] : $this->getIdents();
                $autoliquidaciones = $autliquidacionRepo->findByIdentificaciones($periodo, $idents, $overwrite);
            }
            $this->autoliquidacionesEmpleado = $autoliquidaciones;
        }

        return $this->autoliquidacionesEmpleado;

    }
}