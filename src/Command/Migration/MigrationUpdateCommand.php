<?php


namespace App\Command\Migration;


use App\Entity\Usuario;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationUpdateCommand extends MigrationCommand
{
    public static $defaultName = "sel:migration:update";

    protected function configure()
    {
        parent::configure();
        $this->setDescription("Ejecuta los commandos de migracion para los usuarios que falten")
            ->addOption('info', 'i', InputOption::VALUE_NONE, 'Solo muestra informacion, no hace nada');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $info = $input->getOption('info');


        $dql = "SELECT u.idOld from " . Usuario::class . " u ORDER BY u.id DESC";
        $query = $this->getDefaultManager()->createQuery($dql);
        $query->setMaxResults(1)->setFirstResult(0);
        $lastIdOld = $query->getSingleScalarResult();

        $this->io->text("last id old : " . $lastIdOld);

        $sql = "SELECT * from usuario WHERE id > " . $lastIdOld;
        $count = $this->countSql($sql);

        $this->io->text("total usuarios nuevos: " . $count);
        $this->initProgressBar($count);


        while ($row = $this->fetch($sql)) {
            $this->io->text(sprintf('%10s %20s', $row['ident'], $row['primer_nombre'] . " " . $row['primer_apellido']));
        }
    }
}