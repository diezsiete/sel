<?php


namespace App\Command\ServicioEmpleados\ReportCache;


use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ReportCache;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends TraitableCommand
{
    use SelCommandTrait;

    protected static $defaultName = "sel:se:report-cache:delete";


    protected function configure()
    {
        parent::configure();
        $this
            ->addArgument('usuario', InputArgument::REQUIRED, 'id o ident del usuario')
            ->addOption('reports', 'r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'El o los reportes para borrar cache, si no se especifica borra todos.')
            ->addOption('source', 's', InputOption::VALUE_REQUIRED, 'novasoft o halcon', 'novasoft');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $usuario = $this->findUsuario($input);
        $source = $input->getOption('source');
        if($usuario) {
            $reportsNames = $this->configuracion->servicioEmpleados()->getReportsNames();
            $reports = $input->getOption('reports');
            if(!$reports) {
                $reports = $reportsNames;
            }
            foreach($reports as $report) {
                if(in_array($report, $reportsNames)) {
                    $seEntityName = $this->configuracion->servicioEmpleados()->getReportEntityClass($report);
                    $novasoftEntityName = $this->configuracion->servicioEmpleados()->getReportEntityClass($report, $source);

                    $this->deleteReportEntities($seEntityName, ['usuario' => $usuario, 'source' => $source]);
                    $this->deleteReportEntities($novasoftEntityName, ['usuario' => $usuario]);

                    if($reportCache = $this->em->getRepository(ReportCache::class)
                        ->findLastCacheForReport($usuario, $source, $report)) {
                        $this->em->remove($reportCache);
                    }
                    $this->em->flush();
                } else {
                    $this->io->warning("el reporte '$report', no existe");
                }
            }

        } else {
            $this->io->warning("no usuario found");
        }
    }

    private function findUsuario(InputInterface $input)
    {
        $argument = $input->getArgument('usuario');
        $repository = $this->em->getRepository(Usuario::class);
        $usuario = $repository->find($argument);
        if(!$usuario) {
            $usuario = $repository->findByIdentificacion($argument);
        }
        return $usuario;
    }

    private function deleteReportEntities($entityName, $criteria)
    {
        $entities = $this->em->getRepository($entityName)->findBy($criteria);
        foreach($entities as $entity) {
            $this->em->remove($entity);
        }
    }
}