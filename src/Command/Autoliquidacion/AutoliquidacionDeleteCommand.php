<?php


namespace App\Command\Autoliquidacion;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Service\AutoliquidacionService;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class AutoliquidacionDeleteCommand extends AutoliquidacionCommand
{
    protected static $defaultName = 'sel:autoliquidacion:delete';

    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;

    /**
     * @var DateTimeInterface
     */
    private $periodo;

    /**
     * @var Autoliquidacion[]
     */
    private $autoliquidaciones;
    /**
     * @var AutoliquidacionService
     */
    private $autoliquidacionService;

    public function __construct(AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionService $autoliquidacionService)
    {
        parent::__construct();
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionService = $autoliquidacionService;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addSearchByConvenioOrIdent()
            ->addOption('periodo', 'p', InputOption::VALUE_OPTIONAL,
                'Especifique mes en formato Y-m. Si no se especifica se toman todos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input);
        $autoliquidaciones = $this->findAutoliquidaciones($periodo);


        if($this->isSearchConvenioAll()) {
            $this->autoliquidacionService->deletePdf($periodo);
        } else {
            $identificaciones = $this->autoliquidacionRepository->getEmpleadosIdentificaciones($autoliquidaciones);
            // TODO borrar identificaciones sin periodo
            $this->autoliquidacionService->deletePdf($periodo, $identificaciones);
        }

        foreach($autoliquidaciones as $autoliquidacion) {
            $this->em->remove($autoliquidacion);

            $this->progressBarAdvance();
        }

        $this->em->flush();
    }


    protected function progressBarCount(InputInterface $input, OutputInterface $output): int
    {
        return count($this->findAutoliquidaciones($this->getPeriodo($input)));
    }

    protected function findAutoliquidaciones($periodo)
    {
        if(!is_array($this->autoliquidaciones)) {
            $this->autoliquidaciones = [];
            if($this->isSearchConvenio()) {
                if($this->isSearchConvenioAll()) {
                    $this->autoliquidaciones = $this->autoliquidacionRepository->findByPeriodo($periodo);
                }else{
                    $this->autoliquidaciones = $this->autoliquidacionRepository->findByConvenio($this->getConveniosCodigos(), $periodo);
                }
            } else {
                $this->autoliquidaciones = $this->autoliquidacionRepository->findByIdentificacion($this->getIdents(), $periodo);
            }
        }
        return $this->autoliquidaciones;
    }

    protected function getPeriodo(InputInterface $input)
    {
        if(!$this->periodo) {
            $this->periodo = $input->getOption('periodo') ?
                DateTime::createFromFormat('Y-m-d', $input->getOption('periodo') . "-01") : null;
        }
        return $this->periodo;
    }
}