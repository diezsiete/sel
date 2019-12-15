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

class MigrationEmpleadoCommand extends MigrationCommand
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
        $this->addOption('remain', 'r', InputOption::VALUE_NONE, 'Solo importa aquellos que no tengan empleado creado')
            ->addOption('uid', null, InputOption::VALUE_OPTIONAL,
                'Importar solo uno por id de usuario');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uid = $input->getOption('uid');
        if($uid) {
            $ident = $this->usuarioRepository->findOneBy(['idOld' => $uid])->getIdentificacion();
            if($empleado = $this->empleadoRepository->findByIdentificacion($ident)) {
                $this->io->warning("Empleado ya importado. Haciendo nada");
                $idents = [];
            } else {
                $idents = [$ident];
            }
        } else {
            $remain = $input->getOption('remain');

            $usuariosIds = [];
            if ($remain) {
                $usuariosIds = $this->empleadoRepository->getUsuariosIds();
            }

            $idents = $this->usuarioRepository->findEmpleadosIdents($usuariosIds);
        }

        $count = count($idents);

        $this->initProgressBar($count);

        try {
            foreach ($idents as $ident) {
                $usuario = $this->usuarioRepository->findByIdentificacion($ident);
                if ($empleado = $this->novasoftEmpleadoService->findInNovasoft($ident)) {
                    $empleado->setUsuario($usuario);
                    $this->selPersist($empleado);
                } else {
                    if($usuario->esRol("ROLE_EMPLEADO")) {
                        $usuario->removeRol("ROLE_EMPLEADO")->addRol("ROLE_HALCON");
                        $this->flushAndClear();
                    }
                    $this->progressBar->advance();
                }
            }
        } catch (Exception $e) {
            //dump($e);
            throw $e;
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Empleado::class);
    }

}