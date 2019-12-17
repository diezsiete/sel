<?php


namespace App\Command\NovasoftImport;


use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\RangoPeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Service\Novasoft\Report\ReportFactory;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NiLiquidacionNominaCommand extends TraitableCommand
{
    use PeriodoOption,
        RangoPeriodoOption,
        SearchByConvenioOrEmpleado;

    protected static $defaultName = "sel:ni:liquidacion-nomina";
    /**
     * @var ReportFactory
     */
    private $reportFactory;


    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                 ReportFactory $reportFactory)
    {
        $this->disableSearchEmpleado = true;
        parent::__construct($annotationReader, $eventDispatcher);
        $this->reportFactory = $reportFactory;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {




        $inicio = $this->getInicio($input);
        $fin = $this->getFin($input);
        $convenios = $this->getConvenios();

        foreach($convenios as $convenio) {
            $this->reportFactory
                ->liquidacionNomina($convenio, $inicio, $fin)
                ->importMap();
        }
    }
}