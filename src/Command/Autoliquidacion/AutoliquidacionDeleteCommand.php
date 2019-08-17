<?php


namespace App\Command\Autoliquidacion;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\ConsoleTrait;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Service\AutoliquidacionService;
use DateTimeInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class AutoliquidacionDeleteCommand extends TraitableCommand
{
    use PeriodoOption,
        SearchByConvenioOrEmpleado,
        ConsoleProgressBar,
        ConsoleTrait;

    protected static $defaultName = 'sel:autoliquidacion:delete';

    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;

    /**
     * @var Autoliquidacion[]
     */
    private $autoliquidaciones;
    /**
     * @var AutoliquidacionService
     */
    private $autoliquidacionService;

    public function __construct(Reader $reader, EventDispatcherInterface $dispatcher,
                                AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionService $autoliquidacionService)
    {
        parent::__construct($reader, $dispatcher);
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionService = $autoliquidacionService;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input, false);
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
        return count($this->findAutoliquidaciones($this->getPeriodo($input, false)));
    }

    protected function findAutoliquidaciones(?DateTimeInterface $periodo = null)
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
}