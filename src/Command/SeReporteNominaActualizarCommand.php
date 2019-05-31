<?php

namespace App\Command;

use App\Entity\Usuario;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use App\Repository\UsuarioRepository;
use App\Service\NovasoftImport\ReporteNominaImport;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SeReporteNominaActualizarCommand extends PeriodoCommand
{
    protected static $defaultName = 'se:reporte-nomina-actualizar';

    protected $optionDesdeDescription = 'fecha desde Y-m-d. [omita y se toma desde 2017-02-01]';
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;
    /**
     * @var NovasoftSsrs
     */
    private $novasoftSsrs;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;
    /**
     * @var ReporteNominaImport
     */
    private $reporteNominaImport;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UsuarioRepository $usuarioRepository, ConvenioRepository $convenioRepository,
                                EmpleadoRepository $empleadoRepository, NovasoftSsrs $novasoftSsrs,
                                ReporteNominaImport $reporteNominaImport, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->usuarioRepository = $usuarioRepository;
        $this->convenioRepository = $convenioRepository;
        $this->novasoftSsrs = $novasoftSsrs;
        $this->empleadoRepository = $empleadoRepository;
        $this->reporteNominaImport = $reporteNominaImport;
        $this->em = $em;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Actualizar reportes de nomina')
            ->addArgument('codigos', InputArgument::IS_ARRAY,
                'convenio codigos o identificaciones de empleados');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);


        $desde = $this->getInicio($input, true);
        $hasta = $this->getFin($input);

        $usuarios = $this->getUsuarios($input->getArgument('codigos'));
        foreach($usuarios as $usuario) {
            $reportesNomina = $this->novasoftSsrs->getReporteNomina($usuario, $desde, $hasta);
            foreach($reportesNomina as $reporteNomina) {
                if($reporteDb = $this->reporteNominaImport->exists($reporteNomina)) {
                    $this->reporteNominaImport->update($reporteDb, $reporteNomina);
                } else {
                    $this->reporteNominaImport->insert($reporteNomina);
                }
            }
            $this->em->flush();
        }

    }

    /**
     * @param array $codigos
     * @return Usuario[]
     */
    protected function getUsuarios($codigos)
    {
        $usuarios = [];
        if($codigos) {
            if (is_numeric($codigos[0])) {
                $usuarios = $this->usuarioRepository->findBy(['identificacion' => $codigos]);
            } else {
                // TODO se puede optimizar con una sola consulta que retorne los usuarios de convenios
                $convenios = $this->convenioRepository->findBy(['codigo' => $codigos]);
                foreach ($convenios as $convenio) {
                    foreach($convenio->getEmpleados() as $empleado) {
                        $usuarios[] = $empleado->getUsuario();
                    }
                }
            }
        } else {
            $usuarios = $this->empleadoRepository->findAllUsuarios();
        }

        return $usuarios;
    }
}
