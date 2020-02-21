<?php


namespace App\Command\NovasoftApi\Hv;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Hv\Hv;
use App\Repository\Hv\HvRepository;
use App\Service\Novasoft\Api\Client\HvClient;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class NapiHvCommand extends TraitableCommand
{
    /**
     * @var HvClient
     */
    protected $hvClient;
    /**
     * @var HvRepository
     */
    protected $hvRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                HvClient $hvClient, HvRepository $hvRepository)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->hvClient = $hvClient;
        $this->hvRepository = $hvRepository;
    }

    protected function configure()
    {
        return $this
            ->setDescription('generar un llamado post para una hv especifica')
            ->addArgument('id', InputArgument::REQUIRED, 'id o identificacion de la hv a crear');
    }

    /**
     * @return Hv
     */
    protected function findHv(): Hv
    {
        $id = $this->input->getArgument('id');
        $hv = $this->hvRepository->findByIdentificacion($id);
        if(!$hv) {
            $hv = $this->hvRepository->find($id);
            if(!$hv) {
                $hv = $this->hvRepository->findByUsuario($id);
            }
        }
        if(!$hv) {
            throw new \RuntimeException("No usuario encontrado para el id '$id'");
        }
        return $hv;
    }
}