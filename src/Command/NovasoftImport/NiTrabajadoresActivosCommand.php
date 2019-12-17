<?php


namespace App\Command\NovasoftImport;


use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Service\Novasoft\Report\Import\ImportReportService;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NiTrabajadoresActivosCommand extends TraitableCommand
{
    use Loggable,
        PeriodoOption,
        SearchByConvenioOrEmpleado,
        SelCommandTrait;

    protected static $defaultName = "sel:ni:trabajadores-activos";
    /**
     * @var ImportReportService
     */
    private $importReportService;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                ImportReportService $importReportService)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->importReportService = $importReportService;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input, false);
        $convenios = $this->getConvenios();

        $this->importReportService->trabajadoresActivos($convenios, $periodo);
    }
}