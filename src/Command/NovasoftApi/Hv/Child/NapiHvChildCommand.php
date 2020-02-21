<?php

namespace App\Command\NovasoftApi\Hv\Child;

use App\Command\NovasoftApi\Hv\NapiHvCommand;
use App\Entity\Hv\Hv;
use App\Repository\Hv\HvRepository;
use App\Service\Novasoft\Api\Client\HvClient;
use App\Service\Utils\Varchar;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class NapiHvChildCommand extends NapiHvCommand
{

    /**
     * @var Varchar
     */
    private $varcharUtil;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher, HvClient $hvClient,
                                HvRepository $hvRepository, Varchar $varcharUtil)
    {
        parent::__construct($annotationReader, $dispatcher, $hvClient, $hvRepository);
        $this->varcharUtil = $varcharUtil;
    }

    protected function configure()
    {
        return parent::configure()
            ->addArgument('entity', InputArgument::REQUIRED, 'child entity name');
    }

    protected function getChildEntityClassName()
    {
        $entity = $this->input->getArgument('entity');
        $baseClass = preg_replace('/^(.+?)(\w+?)$/', '$1', Hv::class);
        if(class_exists($baseClass . $entity)) {
            return $baseClass . $entity;
        }
        $baseClass .= $this->varcharUtil->pascalize($entity);
        if(!class_exists($baseClass)) {
            throw new \Exception("La entidad '$entity' no fue encontrada");
        }
        return $baseClass;
    }

}