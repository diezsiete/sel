<?php

namespace App\Command\ServicioEmpleados\ReportCache;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Command\Helpers\ServicioEmpleados\ReportCache as ReportCacheTrait;
use App\Constant\ServicioEmpleadosConstant;
use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use DateTime;
use Doctrine\ORM\Query;
use SSRS\SSRSReportException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
// Necesario por bug de ReportCacheTrait utiliza trait ConnectToLogEvent que necesita esta y no es importada automaticamente
use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;

class ImportCommand extends TraitableCommand
{
    use SelCommandTrait,
        ReportCacheTrait,
        ConsoleProgressBar;

    protected static $defaultName = "sel:se:report-cache:import";

    protected function configure()
    {
        $this->usuarioArgumentDescription = 'id o ident del usuario, o codigo convenio. Si se deja vacio toma todos';
        parent::configure();
        $this
            ->addOption('hard', null, InputOption::VALUE_NONE, 'Borra todo e importa todo')
            ->addOption('ignore-refresh-interval', null, InputOption::VALUE_NONE, 'Ignora el refresh interval')
            ->addOption('all', null, InputOption::VALUE_NONE,
                'Para source novasoft, toma todos los empleados, de lo contrario solo toma los activos 
                (cuya fecha retiro sea null o fecha retiro se mayor al primer dia del mes actual)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $this->getSource($input);
        $hard = $input->getOption('hard');
        $ignoreRefreshInterval = $input->getOption('ignore-refresh-interval');


        foreach($this->findUsuario($input) as $usuario) {
            foreach($this->getReports($input) as $report) {
                if($hard) {
                    $this->reportCacheHandler->delete($usuario, $report, $source);
                }
                if($source === 'novasoft') {
                    try {
                        $this->reportCacheHandler->handleNovasoft($usuario, $report, $ignoreRefreshInterval);
                    } catch (SSRSReportException $e) {
                        $this->error(get_class($e) . ": " . $e->errorDescription);
                        //throw $e;
                    }
                } else {
                    $this->reportCacheHandler->handleHalcon($usuario, $report);
                }
                $this->progressBarAdvance();
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        $countUsuarios = (int)$this->findUsuarioQuery($input, true)->getSingleScalarResult();
        $reportsCount = count($this->getReports($input));
        return $countUsuarios * $reportsCount;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param InputInterface $input
     * @param bool $count
     * @return Query
     */
    protected function findUsuarioQuery(InputInterface $input, $count = false)
    {
        $source = $input->getOption('source');
        $usuario = $input->getArgument('usuario');
        $all = $input->getOption('all');
        if($source === ServicioEmpleadosConstant::SOURCE_HALCON || ($usuario && is_numeric($usuario))) {
            $rol = $this->configuracion->servicioEmpleados()->getRolBySource($source);
            $usuarioRepository = $this->em->getRepository(Usuario::class);

            return $count
                ? $usuarioRepository->countByRolQuery($rol, $usuario)
                : $usuarioRepository->findByRolQuery($rol, $usuario);
        } else {
            $empleadoRepository = $this->em->getRepository(Empleado::class);
            $activo = $all ? false : DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m-') . '01');
            if(!$usuario) {
                return $count
                    ? $empleadoRepository->countAllUsuariosQuery($activo)
                    : $empleadoRepository->findAllUsuariosQuery($activo);
            }
            //usuario es el codigo de un convenio
            else {
                return $count
                    ? $empleadoRepository->countByConvenioQuery($usuario, $activo)
                    : $empleadoRepository->findByConvenioQuery($usuario, $activo);
            }
        }
    }
}