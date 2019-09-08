<?php


namespace App\Command\Autoliquidacion;



use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Scraper\AutoliquidacionScraper;
use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Response\ResponseManager;
use Doctrine\Common\Annotations\Reader;
use Exception;
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
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;
    /**
     * @var AutoliquidacionScraper
     */
    private $scrapper;

    /**
     * @var string[]
     */
    private $identificaciones = null;

    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var AutoliquidacionEmpleadoRepository
     */
    private $autoliquidacionEmpleadoRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepository,
                                AutoliquidacionScraper $scrapper, Configuracion $configuracion)
    {
        parent::__construct($annotationReader, $eventDispatcher);

        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->scrapper = $scrapper;
        $this->configuracion = $configuracion;
        $this->autoliquidacionEmpleadoRepository = $autoliquidacionEmpleadoRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('overwrite', 'o', InputOption::VALUE_NONE,
            'Vuelve y descarga la autoliquidacion aun halla sido exitosa');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input);
        $identificaciones = $this->getIdentificaciones($periodo);
        $empleador = $this->configuracion->getScrapper()->ael()->empleador;

        if($identificaciones) {
            $this->scrapper->launch($empleador);
            try {
                foreach ($identificaciones as $ident) {
                    try {
                        $autoliquidacionEmpleado = $this->autoliquidacionEmpleadoRepository->findByIdentPeriodo($ident, $periodo);
                        $autoliquidacion = $autoliquidacionEmpleado->getAutoliquidacion();

                        $exito = true;
                        $code = ResponseManager::OK;
                        $salida = "PDF descargado exitosamente";

                        try {
                            $this->scrapper->generatePdf($ident, $periodo);
                            $this->scrapper->downloadPdf($ident, $periodo);
                            $this->scrapper->deletePdf($ident, $periodo);
                        } catch (ScraperNotFoundException $e) {
                            $code = ResponseManager::NOTFOUND;
                            $salida = $e->getMessage();
                        } catch (ScraperException $e) {
                            $exito = false;
                            $code = ResponseManager::ERROR;
                            $salida = $e->getMessage();
                        }

                        $autoliquidacionEmpleado->setExito($exito)->setCode($code)->setSalida($salida);
                        $autoliquidacion->calcularPorcentajeEjecucion();
                        $this->em->flush();

                        try {
                            $this->scrapper->clearIdent();
                        } catch (ScraperConflictException $e) {
                            $this->scrapper->reload();
                        }
                    } catch (ScraperException $e) {
                        $this->scrapper
                            ->logout()
                            ->close()
                            ->launch($empleador);
                    }

                    $this->progressBarAdvance();
                }
                $this->scrapper->close();

            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            $this->scrapper->close();
        } else {
            $this->io->writeln("No hay autolquidaciones para descargar");
        }
    }


    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->getIdentificaciones($this->getPeriodo($input), $input->getOption('overwrite')));
    }

    protected function getIdentificaciones($periodo, $overwrite = false)
    {
        $noExito = !$overwrite;
        if(!is_array($this->identificaciones)) {
            $this->identificaciones = [];
            if($this->isSearchConvenio()) {
                if(!$this->searchValue) {
                    $this->identificaciones = $this->autoliquidacionRepository
                        ->getIdentificacionesByPeriodo($periodo, $noExito);
                } else {
                    $this->identificaciones = $this->autoliquidacionRepository
                        ->getIdentificacionesByConvenio($this->searchValue, $periodo, $noExito);
                }
            } else {
                $this->identificaciones = $this->getIdents();
            }
        }
        return $this->identificaciones;
    }
}