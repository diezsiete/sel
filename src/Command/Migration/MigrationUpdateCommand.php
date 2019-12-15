<?php


namespace App\Command\Migration;


use App\Command\Helpers\SelCommandTrait;
use App\Entity\Usuario;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationUpdateCommand extends MigrationCommand
{
    use SelCommandTrait;
    
    public static $defaultName = "sel:migration:update";

    protected function configure()
    {
        parent::configure();
        $this->setDescription("Ejecuta los commandos de migracion para los usuarios que falten")
            ->addArgument('id', InputArgument::OPTIONAL, 'Solo importa este id')
            ->addOption('info', 'i', InputOption::VALUE_NONE,
                'Solo muestra informacion, no hace nada');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $info = $input->getOption('info');
        $limit = $input->getOption('limit');

        $dql = "SELECT u.idOld FROM " . Usuario::class . " u WHERE u.idOld IS NOT NULL ORDER BY u.id DESC";

        $query = $this->getDefaultManager()->createQuery($dql);

        $query->setMaxResults(1)->setFirstResult(0);
        $lastIdOld = $query->getSingleScalarResult();

        $this->io->text("last id old : " . $lastIdOld);

        $sql = "SELECT * FROM usuario WHERE id > " . $lastIdOld;
        if($id) {
            $sql .= " AND id = " . $id;
        }
        else if($limit) {
            $sql .= " LIMIT $limit";
        }

        $count = $this->countSql($sql);

        $this->io->text("total usuarios nuevos: " . $count);
        $this->initProgressBar($count);


        while ($row = $this->fetch($sql)) {
            $this->io->text(sprintf('%10s %20s', $row['ident'], $row['primer_nombre'] . " " . $row['primer_apellido']));
            
            $this->runCommand($output, 'sel:migration:usuario', [], ['id' => $row['id'], 'i' => $info]);

            if(!$info) {
                $this->runCommand($output, 'sel:migration:hv', [], ['uid' => $row['id']]);
                $this->runCommand($output, 'sel:migration:hv-entity', [], ['uid' => $row['id']]);
                $this->runCommand($output, 'sel:migration:hv-adjunto', [], ['uid' => $row['id']]);
                $this->runCommand($output, 'sel:migration:evaluacion-resultado', [], ['uid' => $row['id']]);
                $this->runCommand($output, 'sel:migration:empleado', [], ['uid' => $row['id']]);
            }
        }

    }
}