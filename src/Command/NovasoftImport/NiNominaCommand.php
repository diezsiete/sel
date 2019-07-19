<?php

namespace App\Command\NovasoftImport;

use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\ReporteNomina;
use App\Entity\Usuario;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use App\Repository\ReporteNominaRepository;
use App\Repository\UsuarioRepository;
use App\Service\Configuracion\SsrsDb;
use App\Service\NovasoftImport\ReporteNominaImport;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class NiNominaCommand extends PeriodoCommand
{
    protected static $defaultName = 'sel:ni:nomina';

    protected $optionDesdeDescription = 'fecha desde Y-m-d. [omita y se toma desde 2017-02-01]';

    /**
     * @var NovasoftSsrs
     */
    private $novasoftSsrs;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;

    /**
     * @var ReporteNominaRepository
     */
    private $reporteNominaRepository;


    private $empleadoSsrsDbs = [];


    public function __construct(EmpleadoRepository $empleadoRepository, NovasoftSsrs $novasoftSsrs,
                                ReporteNominaRepository $reporteNominaRepository)
    {
        parent::__construct();

        $this->novasoftSsrs = $novasoftSsrs;
        $this->empleadoRepository = $empleadoRepository;
        $this->reporteNominaRepository = $reporteNominaRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Actualizar reportes de nomina')
            ->addArgument('codigos', InputArgument::IS_ARRAY,
                'convenio codigos o identificaciones de empleados')
            ->addOption('dont-update', null, InputOption::VALUE_NONE,
                'Si certificado ya existe no actualiza');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $desde = $this->getInicio($input, true);
        $hasta = $this->getFin($input);
        $dontUpdate = $input->getOption('dont-update');
    
        $empleados = $this->getEmpleados($input->getArgument('codigos'));


        foreach($empleados as $empleado) {
            $this->io->writeln(sprintf("%s %d %d %s", 
                $empleado->getConvenio()->getNombre(), 
                $empleado->getUsuario()->getId(), 
                $empleado->getUsuario()->getIdentificacion(), 
                $empleado->getUsuario()->getNombreCompleto(true)));
            
            $reportesNomina = $this->novasoftSsrs
                ->setSsrsDb($this->getEmpleadoSsrsDb($empleado))
                ->getReporteNomina($empleado->getUsuario(), $desde, $hasta);
            
            foreach($reportesNomina as $reporteNomina) {
                $reporteNominaDb = $this->exists($reporteNomina);
                $update = $reporteNominaDb && !$dontUpdate;
                if($reporteNominaDb) {
                    $message = "not updated";
                    if($update) {
                        $message = "updated";
                        $this->update($reporteNominaDb, $reporteNomina);
                    }
                } else {
                    $message = "inserted";
                    $this->insert($reporteNomina);
                }

                $this->io->writeln(sprintf("%10s %s", $reporteNomina->getFecha()->format('Y-m'), $message));
            }
            $this->em->flush();
        }
    }

    /**
     * @param array $codigos
     * @return Empleado[]
     */
    protected function getEmpleados($codigos)
    {
        if($codigos) {
            if (is_numeric($codigos[0])) {
                $empleados = $this->empleadoRepository->findByIdentificacion($codigos);
            } else {
                $empleados = $this->empleadoRepository->findByConvenio($codigos);
            }
        } else {
            $empleados = $this->empleadoRepository->findAll();
        }
        return $empleados;
    }

    /**
     * @param Empleado $empleado
     * @return SsrsDb
     * @throws \Exception
     */
    private function getEmpleadoSsrsDb(Empleado $empleado)
    {
        if(!isset($this->empleadoSsrsDbs[$empleado->getSsrsDb()])) {
            $this->empleadoSsrsDbs[$empleado->getSsrsDb()] = $this->configuracion->getSsrsDb($empleado->getSsrsDb());
        }
        return $this->empleadoSsrsDbs[$empleado->getSsrsDb()];
    }

    private function exists(ReporteNomina $reporteNomina)
    {
        $reporteNominaDb = $this->reporteNominaRepository->findByFecha($reporteNomina->getUsuario(), $reporteNomina->getFecha());
        return $reporteNominaDb;
    }

    private function insert(ReporteNomina $reporteNomina)
    {
        $this->em->persist($reporteNomina);
        return true;
    }

    private function update(ReporteNomina $target, ReporteNomina $external)
    {
        $this->em->remove($target);
        return $this->insert($external);
    }
}
