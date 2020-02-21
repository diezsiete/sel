<?php


namespace App\Command\NovasoftApi\Hv;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\Hv\HvRepository;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GetLocalCommand extends TraitableCommand
{
    protected static $defaultName = 'sel:napi:hv:get-local';
    /**
     * @var HvRepository
     */
    private $hvRepository;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;


    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                HvRepository $hvRepository, NormalizerInterface $normalizer)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->hvRepository = $hvRepository;
        $this->normalizer = $normalizer;
    }

    protected function configure()
    {
        $this->setDescription('Obtener serializaada via hv local para pruebas')
            ->addArgument('identificacion', InputArgument::REQUIRED, 'identificacion')
            ->addArgument('group', InputArgument::OPTIONAL, 'serializacion group',
                'napi:hv:post')
            ->addOption('pretty', 'p', InputOption::VALUE_NONE, 'Imprimir pretty');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hv = $this->hvRepository->findByIdentificacion($input->getArgument('identificacion'));
        $group = $input->getArgument('group');
        $pretty = $input->getOption('pretty');
        if($hv) {
            $hvNormalized = $this->normalizer->normalize($hv, null, ['groups' => [$group]]);
            if($pretty) {
                $this->io->write(print_r($hvNormalized, 1));
            } else {
                $this->io->write(json_encode($hvNormalized));
            }
        }
    }
}