<?php

namespace App\Command;

use App\Repository\HvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HvDeleteOrphanCommand extends Command
{
    protected static $defaultName = 'hv:delete-orphan';
    /**
     * @var HvRepository
     */
    private $hvRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(HvRepository $hvRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->hvRepository = $hvRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Borra las hojas de vida sin usuario. (Usuarios que no completaron proceso de registro)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $orphanHvs = $this->hvRepository->findBy(['usuario' => null]);

        $batchSize = 20;
        $i = 0;
        foreach($orphanHvs as $orphanHv) {
            $i++;
            $this->em->remove($orphanHv);
            if (($i % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear();
            }

        }
        $this->em->flush();
        $this->em->clear();
    }
}
