<?php


namespace App\Command\NovasoftApi\Import;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Empleado;
use App\Entity\Napi\ServicioEmpleados\CertificadoIngresos;
use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Service\Novasoft\Api\Client\NapiClient;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Query\QueryException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CertificadoIngresosImportCommand extends TraitableCommand
{
    use SelCommandTrait,
        SearchByConvenioOrEmpleado,
        ConsoleProgressBar;

    protected static $defaultName = 'sel:napi:import:certificado-ingresos';
    /**
     * @var NapiClient
     */
    private $napiClient;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher, NapiClient $napiClient)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->napiClient = $napiClient;
    }

    protected function configure()
    {
        $this->setDescription('importar certificado ingresos desde napi')
            ->addArgument('year', InputArgument::REQUIRED)
            ->addOption('delete', 'd', InputOption::VALUE_NONE);
        parent::configure();
    }

    /**
     * @param string[] $conveniosCodigos
     * @throws QueryException
     */
    protected function executeConvenios($conveniosCodigos): void
    {
        $delete = $this->input->getOption('delete');
        $rangoStart = new DateTime($this->input->getArgument('year') . '-01-01');
        $rangoEnd = new DateTime($this->input->getArgument('year') . '-12-31');
        foreach($conveniosCodigos as $codigo) {
            $empleados = $this->empleadoRepository->findByRangoPeriodo($rangoStart, $rangoEnd, $codigo);
            foreach ($empleados as $empleado) {
                $delete ? $this->deleteCertificado($empleado) : $this->importCertificado($empleado);
                $this->progressBarAdvance();
            }
        }
    }

    /**
     * @param Empleado|Empleado[]|null
     */
    protected function executeEmpleados($empleados): void
    {
        $delete = $this->input->getOption('delete');
        $empleados = is_array($empleados) ? $empleados : [$empleados];
        foreach($empleados as $empleado) {
            $delete ? $this->deleteCertificado($empleado) : $this->importCertificado($empleado);
            $this->progressBarAdvance();
        }
    }

    protected function importCertificado(Empleado $empleado): void
    {
        $certificadoIngresos = $this->napiClient->itemOperations(CertificadoIngresos::class)->get(
            $empleado->getUsuario()->getIdentificacion(),
            $this->input->getArgument('year') . '-01-01',
            $this->input->getArgument('year') . '-12-31'
        );
        if($certificadoIngresos) {
            if (!$certificadoIngresos->getId()) {
                $certificadoIngresos->setUsuario($empleado->getUsuario());
                $this->em->persist($certificadoIngresos);
                $this->em->flush();
                $this->dispatchImportEvent($certificadoIngresos);
            } else {
                $this->em->flush();
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): int
    {
        $rangoStart = new DateTime($this->input->getArgument('year') . '-01-01');
        $rangoEnd = new DateTime($this->input->getArgument('year') . '-12-31');
        if($this->isSearchConvenio()) {
            return $this->empleadoRepository->countByRango($rangoStart, $rangoEnd, $this->getConveniosCodigos());
        }
        return $this->empleadoRepository->countByRango($rangoStart, $rangoEnd, $this->searchValue);
    }

    protected function deleteCertificado(Empleado $empleado)
    {
        $certificado = $this->em->getRepository(CertificadoIngresos::class)->findOneBy([
            'usuario' => $empleado->getUsuario(),
            'fechaInicial' => new DateTime($this->input->getArgument('year') . '-01-01'),
            'fechaFinal' => new DateTime($this->input->getArgument('year') . '-12-31')
        ]);
        if($certificado) {
            $this->dispatchDeleteEvent($certificado->getId(), \App\Entity\Napi\ServicioEmpleados\CertificadoIngresos::class);
            $this->em->remove($certificado);
            $this->em->flush();
        }
    }

    protected function dispatchImportEvent($entity)
    {
        $this->dispatcher->dispatch(new ImportEvent($entity));
    }

    protected function dispatchDeleteEvent($equalIdentifier, $entityClass)
    {
        $this->dispatcher->dispatch(new DeleteEvent($equalIdentifier, $entityClass));
    }
}