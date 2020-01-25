<?php


namespace App\Command\Helpers\ServicioEmpleados;


use App\Command\Helpers\ConnectToLogEvent;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use App\Entity\Main\Usuario;
use App\Event\Event\LogEvent;
use App\Repository\Main\UsuarioRepository;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use Doctrine\ORM\Query;
use Exception;
use Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

trait ReportCache
{
    use Loggable {
        log as loggableLog;
    }
    use ConnectToLogEvent;

    protected $reportsOptionDescription = "El o los reportes para afectar";

    /**
     * @var ReportCacheHandler
     */
    protected $reportCacheHandler;

    /**
     * @var Usuario
     */
    protected $currentUsuario;

    /**
     * @Configure
     */
    public function addReportCacheArgumentsAndOptions()
    {
        $this
            ->addArgument('usuario', InputArgument::OPTIONAL, 'id o ident del usuario')
            ->addOption('reports', 'r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, $this->reportsOptionDescription)
            ->addOption('source', 's', InputOption::VALUE_REQUIRED, 'novasoft o halcon', 'novasoft');
        return $this;
    }

    /**
     * @param ReportCacheHandler $reportCacheHandler
     * @required
     */
    public function setReportCacheHandler(ReportCacheHandler $reportCacheHandler)
    {
        $this->reportCacheHandler = $reportCacheHandler;
    }

    /**
     * @param InputInterface $input
     * @return Generator
     */
    protected function findUsuario(InputInterface $input)
    {
        $iterableResult = $this->findUsuarioQuery($input)->iterate();
        while (false !== ($usuario = $iterableResult->next())) {
            $this->currentUsuario = $usuario[0];
            yield $usuario[0];
        }
    }

    protected function getReports(InputInterface $input)
    {
        $reportsNames = $this->configuracion->servicioEmpleados()->getReportsNames();
        $reportsOption = $input->getOption('reports');

        $reports = [];
        if(!$reportsOption) {
            $reports = $reportsNames;
        } else {
            foreach($reportsOption as $reportOption) {
                if (in_array($reportOption, $reportsNames)) {
                    $reports[] = $reportOption;
                } else {
                    $this->io->warning("el reporte '$reportOption', no existe");
                }
            }
        }
        return $reports;
    }

    protected function getSource(InputInterface $input)
    {
        $source = $input->getOption('source');
        if(!in_array($source, $this->configuracion->servicioEmpleados()->getSources())) {
            throw new Exception("source '$source' no existe");
        }
        return $source;
    }


    /**
     * @param $level
     * @param $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $message = $this->currentUsuario->getIdentificacion() . " " . $message;
        $this->loggableLog($level, $message, $context);
    }

    protected function getLogEvent()
    {
        return LogEvent::class;
    }

    /**
     * @param InputInterface $input
     * @param bool $count
     * @return Query
     */
    protected abstract function findUsuarioQuery(InputInterface $input, $count = false);
}