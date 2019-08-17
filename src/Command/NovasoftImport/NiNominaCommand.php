<?php

namespace App\Command\NovasoftImport;

use App\Command\Helpers\ConsoleTrait;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\RangoPeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\ReporteNomina;
use App\Repository\ReporteNominaRepository;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class NiNominaCommand extends TraitableCommand
{
    use RangoPeriodoOption,
        PeriodoOption,
        SearchByConvenioOrEmpleado,
        ConsoleTrait;

    protected static $defaultName = 'sel:ni:nomina';


    /**
     * @var NovasoftSsrs
     */
    private $novasoftSsrs;


    /**
     * @var ReporteNominaRepository
     */
    private $reporteNominaRepository;


    private $empleadoSsrsDbs = [];


    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                NovasoftSsrs $novasoftSsrs, ReporteNominaRepository $reporteNominaRepository)
    {
        $this->optionInicioDescription = 'fecha desde Y-m-d. [omita y se toma desde 2017-02-01]';

        parent::__construct($annotationReader, $eventDispatcher);

        $this->novasoftSsrs = $novasoftSsrs;
        $this->reporteNominaRepository = $reporteNominaRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Actualizar reportes de nomina')
            ->addOption('dont-update', null, InputOption::VALUE_NONE,
                'Si certificado ya existe no actualiza');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $empleados = $this->getEmpleados();

        $desde = $this->getInicio($input, true);
        $hasta = $this->getFin($input);

        $dontUpdate = $input->getOption('dont-update');

        foreach($empleados as $empleado) {
            $this->io->writeln(sprintf("%s %d %d %s",
                $empleado->getConvenio()->getNombre(),
                $empleado->getUsuario()->getId(),
                $empleado->getUsuario()->getIdentificacion(),
                $empleado->getUsuario()->getNombreCompleto(true)));

            $reportesNomina = $this->novasoftSsrs
                ->setSsrsDb($empleado->getSsrsDb())
                ->getReporteNomina($empleado->getUsuario(), $desde, $hasta);

            foreach($reportesNomina as $reporteNomina) {
                $reporteNominaDb = $this->exists($reporteNomina);
                $update = $reporteNominaDb && !$dontUpdate;
                if($reporteNominaDb) {
                    $message = "not updated";
                    if($update) {
                        $message = "updated";
                        $this->update($reporteNominaDb, $reporteNomina);
                    }
                } else {
                    $message = "inserted";
                    $this->insert($reporteNomina);
                }

                $this->io->writeln(sprintf("%10s %s", $reporteNomina->getFecha()->format('Y-m'), $message));
            }
            $this->em->flush();
        }
    }

    private function exists(ReporteNomina $reporteNomina)
    {
        $reporteNominaDb = $this->reporteNominaRepository->findByFecha($reporteNomina->getUsuario(), $reporteNomina->getFecha());
        return $reporteNominaDb;
    }

    private function insert(ReporteNomina $reporteNomina)
    {
        $this->em->persist($reporteNomina);
        return true;
    }

    private function update(ReporteNomina $target, ReporteNomina $external)
    {
        $this->em->remove($target);
        return $this->insert($external);
    }
}
