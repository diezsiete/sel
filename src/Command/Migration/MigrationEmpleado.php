<?php


namespace App\Command\Migration;


use App\Entity\Empleado;
use App\Repository\EmpleadoRepository;
use App\Repository\UsuarioRepository;
use App\Service\Novasoft\NovasoftEmpleadoService;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MigrationEmpleado extends MigrationCommand
{
    public static $defaultName = "sel:migration:empleado";
    /**
     * @var NovasoftEmpleadoService
     */
    private $novasoftEmpleadoService;
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ManagerRegistry $managerRegistry,
                                UsuarioRepository $usuarioRepository, NovasoftEmpleadoService $novasoftEmpleadoService,
                                EmpleadoRepository $empleadoRepository)
    {
        parent::__construct($annotationReader, $eventDispatcher, $managerRegistry);
        $this->usuarioRepository = $usuarioRepository;
        $this->novasoftEmpleadoService = $novasoftEmpleadoService;
        $this->empleadoRepository = $empleadoRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('remain', 'r', InputOption::VALUE_NONE, 'Solo importa aquellos que no tengan empleado creado');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $remain = $input->getOption('remain');

        $usuariosIds = [];
        if($remain) {
            $usuariosIds = $this->empleadoRepository->getUsuariosIds();
        }
        $idents = $this->usuarioRepository->findEmpleadosIdents($usuariosIds);

        $count = count($idents);
        $this->initProgressBar($count);

        try {
            foreach ($idents as $ident) {
                if ($empleado = $this->novasoftEmpleadoService->findInNovasoft($ident)) {
                    $empleado->setUsuario($this->usuarioRepository->findByIdentificacion($ident));
                    $this->selPersist($empleado);
                } else {
                    $this->progressBar->advance();
                }
            }
        } catch (Exception $e) {
            dump($e);
            throw $e;
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Empleado::class);
    }

}