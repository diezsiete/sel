<?php

namespace App\Command\Migration;

use App\Entity\Area;
use App\Entity\Cargo;
use App\Entity\EstudioCodigo;
use App\Entity\EstudioInstituto;
use App\Entity\LicenciaConduccion;
use App\Entity\Profesion;
use App\Entity\VacanteArea;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class MigrationDefaultsCommand extends MigrationCommand
{
    protected static $defaultName = 'sel:migration:defaults';

    private $entitiesData = [
        [
            'sql' => "SELECT * FROM `estudio_codigo`",
            'entity' => EstudioCodigo::class,
            'connection' => self::CONNECTION_SE_ASPIRANTE,
            'columnName' => 'nombre'
        ],
        [
            'sql' => "SELECT * FROM `estudio_instituto`",
            'entity' => EstudioInstituto::class,
            'connection' => self::CONNECTION_SE_ASPIRANTE,
            'columnName' => 'nombre'
        ],
        [
            'sql' => "SELECT * FROM `experiencia_area`",
            'entity' => Area::class,
            'connection' => self::CONNECTION_SE_ASPIRANTE,
            'columnName' => 'nombre'
        ],
        [
            'sql' => "SELECT * FROM `area`",
            'entity' => VacanteArea::class,
            'connection' => self::CONNECTION_SE_VACANTES,
            'columnName' => 'name'
        ],
        [
            'sql' => "SELECT * FROM `cargo`",
            'entity' => Cargo::class,
            'connection' => self::CONNECTION_SE_VACANTES,
            'columnName' => 'name'
        ],
        [
            'sql' => "SELECT * FROM `profesion`",
            'entity' => Profesion::class,
            'connection' => self::CONNECTION_SE_VACANTES,
            'columnName' => 'name'
        ],
        [
            'sql' => "SELECT * FROM `licencia_conduccion`",
            'entity' => LicenciaConduccion::class,
            'connection' => self::CONNECTION_SE_VACANTES,
            'columnName' => 'name'
        ],
    ];

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Importar tablas de apoyo antes de las entidades relacionadas a HV');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = 0;
        foreach($this->entitiesData as $data) {
            $count += $this->countSql($data['sql'], $data['connection']);
        }
        $this->initProgressBar($count);

        foreach($this->entitiesData as $data) {
            $this->executeQuery($data['sql'], $data['entity'], $data['connection'], $data['columnName']);
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        foreach($this->entitiesData as $data) {
            $this->truncateTable($data['entity']);
        }
    }

    /**
     * @param $query
     * @param string $entityClassName
     * @param string $connectionName
     * @param string $nombreColumnName
     */
    protected function executeQuery($query, $entityClassName, $connectionName = self::CONNECTION_SE_ASPIRANTE, $nombreColumnName = 'nombre')
    {
        while ($row = $this->fetch($query, $connectionName)) {
            $entity = new $entityClassName();
            $entity
                ->setId($row['id'])
                ->setNombre($row[$nombreColumnName]);
            $this->selPersist($entity);
        }
    }
}
