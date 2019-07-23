<?php


namespace App\Command\Autoliquidacion;



use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\ConsoleTrait;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrIdent;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Scrapper\AutoliquidacionScrapper;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AutoliquidacionDownloadCommand extends TraitableCommand
{
    use Loggable,
        PeriodoOption,
        SearchByConvenioOrIdent,
        ConsoleProgressBar,
        ConsoleTrait;

    protected static $defaultName = 'sel:autoliquidacion:download';

    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;
    /**
     * @var AutoliquidacionScrapper
     */
    private $autoliquidacionScrapper;

    /**
     * @var string[]
     */
    private $identificaciones = null;

    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionScrapper $autoliquidacionScrapper, Configuracion $configuracion)
    {
        parent::__construct($annotationReader, $eventDispatcher);

        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionScrapper = $autoliquidacionScrapper;
        $this->configuracion = $configuracion;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input);
        $identificaciones = $this->getIdentificaciones($periodo);
        $empleador = $this->configuracion->getScrapper()->ael()->empleador;


        $this->autoliquidacionScrapper->launch($empleador);
        try {
            foreach ($identificaciones as $ident) {
                $response = $this->autoliquidacionScrapper->generatePdf($ident, $periodo);
                if ($response->isOk()) {
                    $this->autoliquidacionScrapper->downloadPdf($ident, $periodo);
                    $this->autoliquidacionScrapper->deletePdf($ident, $periodo);
                    $this->autoliquidacionScrapper->clearIdent();
                }
                $this->progressBarAdvance();
            }
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->autoliquidacionScrapper->close();
    }


    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->getIdentificaciones($this->getPeriodo($input)));
    }

    protected function getIdentificaciones($periodo)
    {
        if(!is_array($this->identificaciones)) {
            $this->identificaciones = [];
            if($this->isSearchConvenio()) {
                if(!$this->searchValue) {
                    $this->identificaciones = $this->autoliquidacionRepository->getIdentificacionesByPeriodo($periodo);
                } else {
                    $this->identificaciones = $this->autoliquidacionRepository->getIdentificacionesByConvenio($this->searchValue, $periodo);
                }
            } else {
                $this->identificaciones = $this->getIdents();
            }
        }
        return $this->identificaciones;
    }
}