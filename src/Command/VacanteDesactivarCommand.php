<?php

namespace App\Command;

use App\Constant\VacanteConstant;
use App\Repository\VacanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class VacanteDesactivarCommand extends Command
{
    protected static $defaultName = 'vacante:desactivar';
    /**
     * @var VacanteRepository
     */
    private $vacanteRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(VacanteRepository $vacanteRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->vacanteRepository = $vacanteRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Desactiva las vacantes vencidas')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $vacantes = $this->vacanteRepository->findActivas();
        foreach($vacantes as $vacante) {
            $vigencia = VacanteConstant::VIGENCIA[$vacante->getVigencia()];

            $createdAt = $vacante->getCreatedAt();
            $intervalSpec = $vigencia['interval_spec'];
            $interval = new \DateInterval($intervalSpec);
            $createdAt->add($interval);

            $now = new \DateTime();

            if($createdAt < $now) {
                $io->writeln(sprintf("%d %s (%s) desactivada",
                    $vacante->getId() , $vacante->getTitulo(), $createdAt->format('Y-m-d')));
                $vacante
                    ->setActiva(false)
                    ->setPublicada(false);
                $this->em->flush();
            }
        }
    }
}
