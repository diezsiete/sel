<?php

namespace App\Command\ServicioEmpleados\ReportCache;

use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Command\Helpers\ServicioEmpleados\ReportCache as ReportCacheTrait;
use App\Entity\ServicioEmpleados\ReportCache;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
// Necesario por bug de ReportCacheTrait utiliza trait ConnectToLogEvent que necesita esta y no es importada automaticamente
use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;

class DeleteCommand extends TraitableCommand
{
    use SelCommandTrait,
        ReportCacheTrait;

    protected static $defaultName = "sel:se:report-cache:delete";


    protected function configure()
    {
        $this->reportsOptionDescription = 'El o los reportes para borrar cache, si no se especifica borra todos.';
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $this->getSource($input);

        foreach($this->findUsuario($input) as $usuario) {
            foreach ($this->getReports($input) as $report) {
                $this->reportCacheHandler->delete($usuario, $report, $source);
            }
        }
    }

    protected function findUsuarioQuery(InputInterface $input, $count = false)
    {
        return $this->em->getRepository(ReportCache::class)
            ->findUsuariosBySourceQuery($this->getSource($input), $input->getArgument('usuario'));
    }

}