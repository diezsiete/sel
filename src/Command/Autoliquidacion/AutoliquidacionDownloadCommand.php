<?php


namespace App\Command\Autoliquidacion;



use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Scrapper\AutoliquidacionScrapper;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AutoliquidacionDownloadCommand extends AutoliquidacionCommand
{
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
     * @var DateTimeInterface
     */
    private $periodo = null;

    /**
     * @var string[]
     */
    private $identificaciones = null;

    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(EntityManagerInterface $em, AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionScrapper $autoliquidacionScrapper, Configuracion $configuracion)
    {
        parent::__construct();
        $this->em = $em;
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionScrapper = $autoliquidacionScrapper;
        $this->configuracion = $configuracion;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addSearchByConvenioOrIdent()
            ->addOptionRequired('periodo', 'p', InputOption::VALUE_REQUIRED,
                'Especifique mes en formato Y-m');

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

    protected function getPeriodo(InputInterface $input)
    {
        if(!$this->periodo) {
            $periodo = $input->getOption('periodo');
            $this->periodo = DateTime::createFromFormat('Y-m-d', "$periodo-01");
        }
        return $this->periodo;
    }
}