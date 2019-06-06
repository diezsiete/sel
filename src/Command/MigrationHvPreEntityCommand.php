<?php

namespace App\Command;

use App\Entity\Area;
use App\Entity\EstudioCodigo;
use App\Entity\EstudioInstituto;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationHvPreEntityCommand extends MigrationCommand
{
    protected static $defaultName = 'migration:hv-pre-entity';

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Importar tablas de apoyo antes de las entidades relacionadas a HV');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sqlEstudioCodigo = "SELECT * FROM `estudio_codigo`";
        $sqlEstudioInstituto = "SELECT * FROM `estudio_instituto`";
        $sqlExperienciaArea = "SELECT * FROM `experiencia_area`";

        $count = $this->countSql($sqlEstudioCodigo);
        $count += $this->countSql($sqlEstudioInstituto);
        $count += $this->countSql($sqlExperienciaArea);

        $this->initProgressBar($count);

        $this->executeQuery($sqlEstudioCodigo, EstudioCodigo::class);
        $this->executeQuery($sqlEstudioInstituto, EstudioInstituto::class);
        $this->executeQuery($sqlExperienciaArea, Area::class);
    }

    /**
     * @param $query
     * @param string $entityClassName
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function executeQuery($query, $entityClassName)
    {
        while ($row = $this->seFetch($query)) {
            $entity = new $entityClassName();
            $entity
                ->setId($row['id'])
                ->setNombre($row['nombre']);
            $this->selPersist($entity);
        }
    }
}
