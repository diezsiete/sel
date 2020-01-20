<?php


namespace App\Command\NovasoftImport\Clientes;


use App\Command\Helpers\NovasoftImport\FormatOption;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\RangoPeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\NovasoftImport\NiCommand;
use App\Service\Novasoft\Report\ReportFactory;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NiListadoNominaCommand extends NiCommand
{
    use PeriodoOption,
        RangoPeriodoOption,
        SearchByConvenioOrEmpleado,
        FormatOption;

    protected static $defaultName = "sel:ni:clientes:listado-nomina";

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
            $report = $this->reportFactory->clientes()->listadoNomina($convenio, $inicio, $fin);
//            foreach($report->renderMap() as $listadoNomina) {
//                dump($listadoNomina->getFechaNomina()->format('Y-m-d'));
//                foreach($listadoNomina->getEmpleados() as $empleadoNomina) {
//                    dump($empleadoNomina->getEmpleado()->getUsuario()->getNombreCompleto());
//                }
//                foreach($listadoNomina->getGrupos() as $grupo) {
//                    dump($grupo->getNombre() . " " . $grupo->getListadoNomina()->getFechaNomina()->format('Y-m-d'));
//                    foreach($grupo->getTotales() as $total) {
//                        dump("    " . $total->getValor() . " [" . $total->getEmpleado()->getIdentificacion() . "]");
//                    }
//                    /*foreach($grupo->getSubgrupos() as $subgrupo) {
//                        dump("    " . $subgrupo->getNombre() . " " . $subgrupo->getCodigo());
//                        foreach($subgrupo->getRenglones() as $renglon) {
//                            dump("        " . $renglon->getCantidad() . ", " . $renglon->getValor() . "[" . $renglon->getEmpleado()->getIdentificacion() . "]");
//                        }
//                    }*/
//                }
//            }
            $report->getImporter()->importMap();
            //$this->import();
        }

    }
}