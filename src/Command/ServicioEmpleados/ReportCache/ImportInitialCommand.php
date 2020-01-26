<?php

namespace App\Command\ServicioEmpleados\ReportCache;


use App\Command\Helpers\BatchProcessing;
use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Command\Helpers\ServicioEmpleados\ReportCache as ReportCacheTrait;
use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ReportCache;
use App\Service\Halcon\Report\ReportFactory as HalconReportFactory;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Query;
use SSRS\SSRSReportException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
// Necesario por bug de ReportCacheTrait utiliza trait ConnectToLogEvent que necesita esta y no es importada automaticamente
use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImportInitialCommand extends TraitableCommand
{
    use SelCommandTrait,
        ReportCacheTrait,
        BatchProcessing,
        ConsoleProgressBar;

    /**
     * @var HalconReportFactory
     */
    private $halconReportFactory;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                HalconReportFactory $halconReportFactory)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->halconReportFactory = $halconReportFactory;
    }

    protected static $defaultName = "sel:se:report-cache:import-initial";

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $this->getSource($input);
        $reportCacheRepo = $this->em->getRepository(ReportCache::class);

        foreach($this->batch($this->getUsuariosIdents($input)) as $identificacion) {
            $usuario = $this->getBatchUsuario($identificacion);
            foreach($this->getReports($input) as $report) {
                if (!$cache = $reportCacheRepo->findLastCacheForReport($usuario, $source, $report)) {
                    if($source === 'novasoft') {
//                        try {
//                            $this->reportCacheHandler->handleNovasoft($usuario, $report, $ignoreRefreshInterval);
//                        } catch (SSRSReportException $e) {
//                            $this->error(get_class($e) . ": " . $e->errorDescription);
//                            throw $e;
//                        }
                    } else {
                        $halconReport = $this->halconReportFactory->getReport($report)
                            ->setUsuario($usuario);
                        foreach($halconReport->renderMap() as $halconEntity) {
                            $seEntity = $halconReport->getImporter()->buildSeEntity($halconEntity);
                            if($seEntity) {
                                $this->em->persist($seEntity);
                            }
                        }
                    }
                    $this->em->persist($this->reportCacheHandler->buildNewCache($usuario, $source, $report));
                }
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return $this->getUsuariosIdents($input, true);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param InputInterface $input
     * @param bool $count
     * @return string[]|int
     */
    protected function getUsuariosIdents(InputInterface $input, $count = false)
    {
        $source = $input->getOption('source');
        $usuario = $input->getArgument('usuario');

        $rol = $this->configuracion->servicioEmpleados()->getRolBySource($source);

        $usuarioRepository = $this->em->getRepository(Usuario::class);

        return $count
            ? $usuarioRepository->countIdsByRol($rol, $usuario)
            : $usuarioRepository->findIdentsByRol($rol, $usuario);


    }

    protected function findUsuarioQuery(InputInterface $input, $count = false)
    {
        // no se usa
    }
}