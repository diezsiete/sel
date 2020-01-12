<?php


namespace App\Command\NovasoftImport;

use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Service\Novasoft\Report\Import\ImportReportService;
use App\Service\Novasoft\Report\ReportFactory;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NiTrabajadorActivoCommand extends NiCommand
{
    use PeriodoOption,
        SearchByConvenioOrEmpleado;

    protected static $defaultName = "sel:ni:trabajador-activo";

    /**
     * @var ReportFactory
     */
    private $reportFactory;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ReportFactory $reportFactory)
    {
        $this->disableSearchEmpleado = true;
        parent::__construct($annotationReader, $eventDispatcher);

        $this->reportFactory = $reportFactory;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $periodo = $this->getPeriodo($input, false);
        $convenios = $this->getConvenios();

        foreach($convenios as $convenio) {
            $this->reportFactory
                ->trabajadoresActivos($convenio, $periodo)
                ->getImporter()
                ->importMap();
        }
    }
}