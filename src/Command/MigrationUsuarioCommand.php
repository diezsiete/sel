<?php

namespace App\Command;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationUsuarioCommand extends Command
{
    protected static $defaultName = 'migration:usuario';
    /**
     * @var ManagerRegistry
     */
    private $doctrine;


    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function configure()
    {
        $this
            ->setDescription('Migracion de usuarios')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conn = $this->doctrine->getConnection('se');
        $sql = "SELECT * FROM estado_civil";
        $stmt = $conn->query($sql);
        while ($row = $stmt->fetch()) {
            dump($row);
        }
    }
}
