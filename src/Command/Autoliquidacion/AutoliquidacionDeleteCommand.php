<?php


namespace App\Command\Autoliquidacion;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Service\Autoliquidacion\FileManager;
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
        SelCommandTrait;

    protected static $defaultName = 'sel:autoliquidacion:delete';

    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;

    /**
     * @var Autoliquidacion|AutoliquidacionEmpleado[]
     */
    private $autoliquidaciones;
    /**
     * @var FileManager
     */
    private $autoliquidacionService;
    /**
     * @var AutoliquidacionEmpleadoRepository
     */
    private $autoliquidacionEmpleadoRepository;

    public function __construct(Reader $reader, EventDispatcherInterface $dispatcher,
                                AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepository,
                                FileManager $autoliquidacionService)
    {
        parent::__construct($reader, $dispatcher);
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionService = $autoliquidacionService;
        $this->autoliquidacionEmpleadoRepository = $autoliquidacionEmpleadoRepository;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input, false);
        $autoliquidaciones = $this->findAutoliquidaciones($periodo);

        if($this->isSearchConvenio()) {
            if($this->isSearchConvenioAll()) {
                $this->autoliquidacionService->deletePdf($periodo);
            } else {
                $identificaciones = [];
                foreach($autoliquidaciones as $autoliquidacion) {
                    $identificaciones = array_merge(
                        $identificaciones,
                        $this->autoliquidacionRepository->getEmpleadosIdentificaciones($autoliquidacion));
                }
                foreach($identificaciones as $ident) {
                    $this->autoliquidacionService->deletePdf($periodo, $ident);
                }
            }
            foreach($autoliquidaciones as $autoliquidacion) {
                $this->em->remove($autoliquidacion);
                $this->progressBarAdvance();
            }
        } else {
            foreach($autoliquidaciones as $autoliquidacion) {
                $this->em->remove($autoliquidacion);
                //TODO modificar porcentaje ejecucion
                $this->progressBarAdvance();
            }
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
                $this->autoliquidaciones = $this->autoliquidacionEmpleadoRepository->findByIdentificaciones($periodo, $this->getIdents());
            }
        }
        return $this->autoliquidaciones;
    }
}