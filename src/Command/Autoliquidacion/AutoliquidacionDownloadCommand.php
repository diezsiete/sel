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
use App\Service\Autoliquidacion\Scraper;
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
     * @var Scraper
     */
    private $scraper;

    /**
     * @var string[]
     */
    private $identificaciones = null;



    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, Scraper $scraper)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->scraper = $scraper;
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
        $identificaciones = $this->getIdentificaciones($periodo, $input->getOption('overwrite'));
        $empleador = $this->configuracion->getScraper()->ael()->empleador;
        $user = $this->configuracion->getScraper()->ael()->user;
        $password = $this->configuracion->getScraper()->ael()->password;

        if($identificaciones) {
            $this->scraper->launch($user, $password, $empleador);
            try {
                foreach ($identificaciones as $ident) {
                    $autoliquidacionEmpleado = $this->em->getRepository(AutoliquidacionEmpleado::class)
                        ->findByIdentPeriodo($ident, $periodo);
                    $autoliquidacion = $autoliquidacionEmpleado->getAutoliquidacion();
                    try {
                        $exito = true;
                        $code = ResponseManager::OK;
                        $salida = "PDF descargado exitosamente";

                        try {
                            $this->scraper
                                ->generatePdf($ident, $periodo)
                                ->downloadPdf($ident, $periodo)
                                ->deletePdf($ident, $periodo);
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
                            $this->scraper->clearIdent();
                        } catch (ScraperConflictException $e) {
                            $this->scraper->reload();
                        }

                    } catch (ScraperException $e) {
                        $this->scraper
                            ->logout()
                            ->close()
                            ->launch($user, $password, $empleador);
                    }

                    $this->progressBarAdvance();
                }
                $this->scraper->close();

            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            $this->scraper->close();
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
        $overwrite = $overwrite === null ? true : $overwrite;

        $autliquidacionRepo = $this->em->getRepository(Autoliquidacion::class);
        if(!is_array($this->identificaciones)) {
            $this->identificaciones = [];
            if($this->isSearchConvenio() && $this->searchValue) {
                $this->identificaciones = $autliquidacionRepo->getIdentificacionesByConvenio($this->searchValue, $periodo, $overwrite);
            } else {
                $idents = $this->isSearchConvenio() ? [] : $this->getIdents();
                $this->identificaciones = $autliquidacionRepo->getIdentificacionesByPeriodo($periodo, $overwrite, $idents);
            }
        }
        return $this->identificaciones;
    }
}