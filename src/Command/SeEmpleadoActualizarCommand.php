<?php

namespace App\Command;

use App\Entity\Convenio;
use App\Repository\ConvenioRepository;
use App\Service\NovasoftImport\EmpleadoImport;
use App\Service\ReportesServicioEmpleados;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SeEmpleadoActualizarCommand extends Command
{
    protected static $defaultName = 'se:empleado-actualizar';
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;
    /**
     * @var EmpleadoImport
     */
    private $empleadoImport;
    /**
     * @var ReportesServicioEmpleados
     */
    private $reportesServicioEmpleados;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ConvenioRepository $convenioRepository, EmpleadoImport $empleadoImport,
                                ReportesServicioEmpleados $reportesServicioEmpleados, EntityManagerInterface $em)
    {
        $this->convenioRepository = $convenioRepository;
        $this->empleadoImport = $empleadoImport;
        parent::__construct();
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Actualizar los empleados desde Novasoft')
            ->addArgument('convenios', InputArgument::IS_ARRAY,
                'convenios codigos para descargar. Omita y se toman todos' )
            ->addOption('desde', null, InputOption::VALUE_OPTIONAL,
                'fecha desde Y-m-d. [omita y se toma hoy]')
            ->addOption('hasta', null, InputOption::VALUE_OPTIONAL,
                'fecha desde Y-m-d. [omita y se toma hoy]')
            ->addOption('periodo', 'p', InputOption::VALUE_OPTIONAL,
                'Omite desde y hasta. Especifica mes en formato Y-m')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $convenios = $this->getConvenios($input->getArgument('convenios'));

        $desde = $this->getDesde($input);
        $hasta = $this->getHasta($input);

        //$this->empleadoImport->import($convenios, $desde, $hasta);

        foreach ($convenios as $convenio) {
            $io->writeln($convenio->getCodigo());
            $empleados = $this->reportesServicioEmpleados->getEmpleados($convenio->getCodigo(), $desde, $hasta);
            foreach ($empleados as $empleado) {
                $io->writeln(sprintf("    %s %s", $empleado->getUsuario()->getNombreCompleto(), $empleado->getUsuario()->getIdentificacion()));
                if(!$this->empleadoImport->updateEmpleado($empleado)) {
                    if($this->empleadoImport->insertEmpleado($empleado)) {
                        $io->writeln("        insertado");
                    } else {
                        $io->writeln("        repetido");
                    }
                } else {
                    $io->writeln("        actualizado");
                }
            }
            $this->em->flush();
        }
    }


    /**
     * @param string[] $conveniosCodigos
     * @return Convenio[]
     */
    private function getConvenios($conveniosCodigos = [])
    {
        return $conveniosCodigos ? $this->convenioRepository->findBy(['codigo' => $conveniosCodigos]) : $this->convenioRepository->findAll();
    }

    /**
     * @param InputInterface $input
     * @return \DateTime|null
     */
    private function getDesde(InputInterface $input)
    {
        $desde = $input->getOption('desde');
        if($periodo = $input->getOption('periodo')) {
            $desde = \DateTime::createFromFormat('Y-m-d', "$periodo-01");
        } else {
            $desde = $desde ? \DateTime::createFromFormat('Y-m-d', $desde) : $desde;
        }
        return $desde;
    }

    /**
     * @param InputInterface $input
     * @return \DateTime|null
     */
    private function getHasta(InputInterface $input)
    {
        $hasta = $input->getOption('hasta');
        if($periodo = $input->getOption('periodo')) {
            $hasta = \DateTime::createFromFormat('Y-m-d', \DateTime::createFromFormat("Y-m-d", "$periodo-01")->format("Y-m-t"));
        } else {
            $hasta = $hasta ? \DateTime::createFromFormat('Y-m-d', $hasta) : $hasta;
        }
        return $hasta;
    }

}
