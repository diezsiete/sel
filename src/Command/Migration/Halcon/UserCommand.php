<?php


namespace App\Command\Migration\Halcon;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Usuario;
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

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                UserRepository $userRepo, UsuarioRepository $usuarioRepository,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->userRepo = $userRepo;
        $this->usuarioRepository = $usuarioRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('info', 'i', InputOption::VALUE_NONE, 'Muestra info, no hace nada');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $info = $input->getOption('info');

        foreach($this->userRepo->findAllToImport() as $user) {
            $usuario = $this->usuarioRepository->findByIdentificacion($user->getUser());
            if(!$usuario) {
                $usuario = (new Usuario())
                    ->setIdentificacion($user->getUser())
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
                $message = "ALREADY USER DOING NOTING";
            }

            if($output->isVeryVerbose()) {
                $output->writeln(sprintf('%-6s %-60s %s', $user->getId(), $user->getNombreCompleto(), $message));
            } else {
                $this->progressBarAdvance();
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return $this->userRepo->countAllToImport();
    }
}