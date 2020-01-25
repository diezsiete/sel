<?php


namespace App\Command\ServicioEmpleados\ReportCache;


use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ReportCache;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ViewCommand extends TraitableCommand
{
    use SelCommandTrait;

    protected static $defaultName = "sel:se:report-cache:view";

    /**
     * @var ReportCacheHandler
     */
    protected $reportCacheHandler;

    /**
     * @param ReportCacheHandler $reportCacheHandler
     * @required
     */
    public function setReportCacheHandler(ReportCacheHandler $reportCacheHandler)
    {
        $this->reportCacheHandler = $reportCacheHandler;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addArgument('usuario', InputArgument::REQUIRED, 'id o ident del usuario')
            ->addOption('sources', 's', InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'novasoft o halcon. Si no especifica, las dos', ['halcon', 'novasoft']);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $usuario = $this->em->getRepository(Usuario::class)
            ->findByRol(['ROLE_EMPLEADO', 'ROLE_HALCON'], $input->getArgument('usuario'));
        $sources = $input->getOption('sources');

        $table = new Table($output);
        if($usuario) {
            $table->setHeaders([
                [new TableCell($usuario->getNombrePrimeros() . ' - ' . $usuario->getIdentificacion(), ['colspan' => 5])],
                ['Source', 'Report', 'Last update', 'Refresh Interval', 'Is Over'],
            ]);
            $reports = $this->configuracion->servicioEmpleados()->getReportsNames();

            for($i = 0; $i < count($sources); $i++) {
                $source = $sources[$i];
                if($i > 0) {
                    $table->addRow(new TableSeparator());
                }
                if($this->configuracion->servicioEmpleados()->usuarioHasRoleForSource($usuario, $source)) {
                    foreach ($reports as $report) {
                        $cache = $this->em->getRepository(ReportCache::class)
                            ->findLastCacheForReport($usuario, $source, $report);
                        if(!$cache) {
                            $row = [$source, $report, new TableCell('Sin definir.', ['colspan' => 3])];
                        } else {
                            $lastUpdate = $cache->getLastUpdate()->format('Y-m-d H:i:s');
                            if($source === 'halcon') {
                                $row = [$source, $report, new TableCell($lastUpdate, ['colspan' => 3])];
                            } else {
                                $refreshIntervalSpec = $this->configuracion->servicioEmpleados()->getReportConfig($report)->getRefreshIntervalSpec();
                                $isOver = $this->reportCacheHandler->isRefreshIntervalOver($report, $cache->getLastUpdate());
                                $row = [$source, $report, $lastUpdate, $refreshIntervalSpec, $isOver ? "yes" : "no"];
                            }
                        }
                        $table->addRow($row);
                    }
                } else {
                    $table->addRow([$source, new TableCell('No es rol.', ['colspan' => 2])]);
                }
            }

        } else {
            $table->setHeaders(["no usuario found"]);
        }
        $table->render();
    }
}