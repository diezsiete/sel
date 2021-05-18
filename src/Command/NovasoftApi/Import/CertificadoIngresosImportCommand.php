<?php

namespace App\Command\NovasoftApi\Import;

use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Empleado;
use App\Entity\Napi\Report\ServicioEmpleados\CertificadoIngresos;
use App\Event\Event\ServicioEmpleados\Report\Importer\DeleteEvent;
use App\Event\Event\ServicioEmpleados\Report\Importer\ImportEvent;
use App\Service\Excel\Factory;
use App\Service\Excel\Read;
use App\Service\Napi\Client\NapiClient;
use App\Service\Napi\Report\Report\CertificadoIngresosReport;
use App\Service\Napi\Report\ReportFactory;
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
    use SelCommandTrait;
    use SearchByConvenioOrEmpleado {
        execute as searchExecute;
    }
    use ConsoleProgressBar;

    protected static $defaultName = 'sel:napi:import:certificado-ingresos';

    /**
     * @var ReportFactory
     */
    private $reportFactory;
    /**
     * @var NapiClient
     */
    private $napiClient;
    /**
     * @var Factory
     */
    private $excelFactory;
    /**
     * @var string
     */
    private $kernelProjectDir;
    /**
     * @var Read
     */
    private $excelReader;

    public function __construct(
        Reader $annotationReader,
        EventDispatcherInterface $dispatcher,
        ReportFactory $reportFactory,
        NapiClient $napiClient,
        Factory $excelFactory,
        string $kernelProjectDir
    ) {
        parent::__construct($annotationReader, $dispatcher);
        $this->reportFactory = $reportFactory;
        $this->napiClient = $napiClient;
        $this->excelFactory = $excelFactory;
        $this->kernelProjectDir = $kernelProjectDir;
    }

    protected function configure()
    {
        $this->setDescription('importar certificado ingresos desde napi')
            ->addArgument('year', InputArgument::REQUIRED)
            ->addOption('delete', 'd', InputOption::VALUE_NONE)
            ->addOption('excel', 'x', InputOption::VALUE_NONE);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('excel')) {
            $this->importExcel((int) $input->getArgument('year'));
        } else {
            $this->searchExecute($input, $output);
        }
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
            $this->em->clear();
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
        /** @var CertificadoIngresosReport $report */
        $report = $this->reportFactory->getReport(CertificadoIngresos::class);
        $report->setCurrentYear((int) $this->input->getArgument('year'));
        $report->import($empleado);
    }

    protected function deleteCertificado(Empleado $empleado)
    {
        $certificado = $this->em->getRepository(CertificadoIngresos::class)->findOneBy([
            'usuario' => $empleado->getUsuario(),
            'fechaInicial' => new DateTime($this->input->getArgument('year') . '-01-01'),
            'fechaFinal' => new DateTime($this->input->getArgument('year') . '-12-31')
        ]);
        if($certificado) {
            $this->dispatchDeleteEvent($certificado->getId(), CertificadoIngresos::class);
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

    /**
     * Toma excel 2020, y lo separa en un archivo por empleado para agilizar acceso
     */
    protected function importExcel(int $ano)
    {
        if ($read = $this->excelReader()) {
            $path = $this->kernelProjectDir . "/var/uploads/certificado-ingresos/$ano";
            $titles = array_map('trim', $read->row(1));
            $lastColumn = $read->columnStringFromIndex(array_key_last($titles) + 1);
            $highestRow = $read->getHighestRow();
            $identificacionIndex = $read->columnIndexFromString('F');

            foreach ($read->range("A2:$lastColumn$highestRow") as $rowIndex => $row) {
                if ($identificacion = $row[$identificacionIndex - 1]) {
                    $write = $this->excelFactory->write($path . "/$identificacion.xlsx");
                    for ($col = 1; $col <= count($row); $col++) {
                        $write->setValue($row[$col - 1], $col, 1);
                    }
                    $write->save();
                }
                $this->progressBarAdvance();
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): int
    {
        $count = 0;
        if ($input->getOption('excel')) {
            if ($read = $this->excelReader()) {
                $count = $read->getHighestRow() - 1;
            }
        } else {
            $rangoStart = new DateTime($this->input->getArgument('year') . '-01-01');
            $rangoEnd = new DateTime($this->input->getArgument('year') . '-12-31');
            $count = $this->isSearchConvenio()
                ? $this->empleadoRepository->countByRango($rangoStart, $rangoEnd, $this->getConveniosCodigos())
                : $this->empleadoRepository->countByRango($rangoStart, $rangoEnd, $this->searchValue);
        }

        return $count;
    }

    protected function excelReader(): ?Read
    {
        if ($this->excelReader === null) {
            $year = $this->input->getArgument('year');
            $path = $this->kernelProjectDir . "/var/uploads/certificado-ingresos";
            if (file_exists("$path/$year.xlsx")) {
                $this->excelReader = $this->excelFactory->read("$path/$year.xlsx");
                $this->excelReader->enableTrim("\xA0\xC2");
            }
        }
        return $this->excelReader;
    }
}