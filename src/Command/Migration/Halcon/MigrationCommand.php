<?php


namespace App\Command\Migration\Halcon;


use App\Command\Helpers\BatchProcessing;
use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Usuario;
use App\Repository\Main\UsuarioRepository;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class MigrationCommand extends TraitableCommand
{
    use SelCommandTrait,
        ConsoleProgressBar,
        BatchProcessing;

    /**
     * @var UsuarioRepository
     */
    protected $usuarioRepo;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher, UsuarioRepository $usuarioRepo)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->usuarioRepo = $usuarioRepo;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('identificaciones', InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
            'Importar identificaciones especificas')
            ->addOption('info', 'i', InputOption::VALUE_NONE, 'Muestra info, no hace nada');
    }

    /**
     * @param $identificacion
     * @return Usuario|null
     */
    protected function getUsuario($identificacion)
    {
        /** @var Usuario|false|null $batchEntity */
        $batchEntity = $this->getBatchEntity('usuario');
        if($batchEntity === false || ($batchEntity && $batchEntity->getIdentificacion() !== $identificacion)) {
            $batchEntity = $this->usuarioRepo->findByIdentificacion($identificacion);
            $this->setBatchEntity('usuario', $batchEntity);
        }
        return $batchEntity;
    }


}