<?php


namespace App\Command\NovasoftImport;


use App\Service\Configuracion\Configuracion;
use App\Service\Configuracion\SsrsDb;
use App\Service\ReportesServicioEmpleados;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class NiCommand extends Command
{
    /**
     * @var Configuracion
     */
    protected $configuracion;
    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ReportesServicioEmpleados
     */
    protected $reportesServicioEmpleados;

    /**
     * @required
     */
    public function setConfiguracion(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    /**
     * @required
     */
    public function setEm(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setReportesServicioEmpleados(ReportesServicioEmpleados $reportesServicioEmpleados)
    {
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
    }

    protected function configure()
    {
        $this
            ->addOption('ssrs_db', null,InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY,
                'Novasoft database')
        ;
    }

    /**
     * @return SsrsDb[]
     * @throws \Exception
     */
    protected function getSsrsDbs()
    {
        $ssrsDbOption = $this->input->getOption('ssrs_db');
        $ssrsDbs = [];
        if($ssrsDbOption) {
            foreach($ssrsDbOption as $ssrsDb) {
                $ssrsDbs[] = $this->configuracion->getSsrsDb($ssrsDb);
            }
        } else {
            $ssrsDbs = $this->configuracion->getSsrsDb();
        }
        return $ssrsDbs;
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->input = $input;
        $this->output = $output;

        return parent::run($input, $output);

    }


}