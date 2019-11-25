<?php


namespace App\Command\Migration;


use App\Entity\Empleado;
use App\Repository\UsuarioRepository;
use App\Service\Novasoft\NovasoftEmpleadoService;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MigrationEmpleado extends MigrationCommand
{
    public static $defaultName = "sel:migration:empleado";
    /**
     * @var NovasoftEmpleadoService
     */
    private $novasoftEmpleadoService;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ManagerRegistry $managerRegistry,
                                UsuarioRepository $usuarioRepository, NovasoftEmpleadoService $novasoftEmpleadoService)
    {
        parent::__construct($annotationReader, $eventDispatcher, $managerRegistry);
        $this->usuarioRepository = $usuarioRepository;
        $this->novasoftEmpleadoService = $novasoftEmpleadoService;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $idents = $this->usuarioRepository->findEmpleadosIdents();
        $count = count($idents);
        $this->initProgressBar($count);

        foreach($idents as $ident) {
            if($empleado = $this->novasoftEmpleadoService->findInNovasoft($ident)) {
                $empleado->setUsuario($this->usuarioRepository->findByIdentificacion($ident));
                $this->selPersist($empleado);
            } else {
                $this->progressBar->advance();
            }
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Empleado::class);
    }

}