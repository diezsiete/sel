<?php


namespace App\Command\Migration\Halcon;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Usuario;
use App\Repository\Halcon\TerceroRepository;
use App\Repository\Halcon\UserRepository;
use App\Repository\Main\UsuarioRepository;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCommand extends TraitableCommand
{
    use ConsoleProgressBar,
        SelCommandTrait;

    protected static $defaultName = "sel:migration:halcon:user";

    /**
     * @var UserRepository
     */
    private $userRepo;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var TerceroRepository
     */
    private $terceroRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                UserRepository $userRepo, TerceroRepository $terceroRepository,
                                UsuarioRepository $usuarioRepository,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->userRepo = $userRepo;
        $this->usuarioRepository = $usuarioRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->terceroRepository = $terceroRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('info', 'i', InputOption::VALUE_NONE, 'Muestra info, no hace nada')
            ->addOption('tercero', null, InputOption::VALUE_NONE,
                'Si utilizar para importar usuarios la tabla tercero , por defecto utiliza user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $info = $input->getOption('info');
        $table = $input->getOption('tercero') ? 'tercero' : 'user';
        $batchSize = 20;
        $i = 0;
        foreach($this->getUserRepo()->findAllToImport() as $user) {
            $identificacion = $table === 'tercero' ? $user->getNitTercer() : $user->getUser();
            $usuario = $this->usuarioRepository->findByIdentificacion($identificacion);
            if(!$usuario) {
                $usuario = (new Usuario())
                    ->setIdentificacion($identificacion)
                    ->addRol('ROLE_HALCON')
                    ->setPrimerNombre($user->getPrimerNombre())
                    ->setSegundoNombre($user->getSegundoNombre())
                    ->setPrimerApellido($user->getPrimerApellido())
                    ->setSegundoApellido($user->getSegundoApellido())
                    ->setEmail($user->getEmail())
                    ->aceptarTerminos();

                if(!$info) {
                    $pss = substr($usuario->getIdentificacion(), -4);
                    $usuario
                        ->setPassword($this->passwordEncoder->encodePassword($usuario, $pss));
                    $this->em->persist($usuario);
                }
                $message = "IMPORTED";
            } else {
                $usuario->addRol('ROLE_HALCON');
                $message = "ALREADY USER DOING NOTING";
            }


            if($output->isVeryVerbose()) {
                $output->writeln(sprintf('%-6s %-60s %s', $identificacion, $user->getNombreCompleto(), $message));
            } else {
                $this->progressBarAdvance();
            }
            if (($i % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear();
            }
            $i++;
        }

        $this->em->flush();
        $this->em->clear();
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return $this->getUserRepo()->countAllToImport();
    }

    /**
     * @return TerceroRepository|UserRepository
     */
    protected function getUserRepo()
    {
        return $this->input->getOption('tercero') ? $this->terceroRepository : $this->userRepo;
    }
}