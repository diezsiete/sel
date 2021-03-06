<?php

namespace App\Command\Migration;

use App\Entity\Main\Ciudad;
use App\Entity\Main\Dpto;
use App\Entity\Main\Pais;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationCiudadCommand extends MigrationCommand
{
    private $ciudadesAdicionales = [
        ['ciudad' => 'SIBERIA', 'dpto' => 'CUNDINAMARCA', 'pais' => 'COLOMBIA'],
    ];

    /**
     * @var Pais
     */
    private $lastPais = null;
    /**
     * @var \App\Entity\Main\Dpto
     */
    private $lastDpto = null;

    protected static $defaultName = 'sel:migration:ciudad';

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Migracion de pais, dpto y ciudad')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sqlPais = "SELECT * FROM `pais`";
        $sqlDpto = "SELECT * FROM `dpto` WHERE id != 0 ORDER BY pais_id";
        $sqlCiudad = "SELECT * FROM `ciudad` WHERE id != 0 ORDER BY pais_id, dpto_id";

        $count = $this->countSql($sqlPais, static::CONNECTION_SE_ASPIRANTE);
        $count += $this->countSql($sqlDpto, static::CONNECTION_SE_ASPIRANTE);
        $count += $this->countSql($sqlCiudad, static::CONNECTION_SE_ASPIRANTE);
        $count += count($this->ciudadesAdicionales);

        $this->initProgressBar($count);

        while($row = $this->fetch($sqlPais, static::CONNECTION_SE_ASPIRANTE)) {
            $pais = (new Pais())
                ->setNombre($row['nombre'])
                ->setNId($row['id']);
            $this->selPersist($pais);
        }

        while($row = $this->fetch($sqlDpto, static::CONNECTION_SE_ASPIRANTE)) {
            $lasPais = $this->getLastPais($row['pais_id']);

            $dpto = (new Dpto())
                ->setNombre($row['nombre'])
                ->setNId($row['id'])
                ->setNPaisId($row['pais_id'])
                ->setPais($lasPais);

            $this->selPersist($dpto);
        }

        $this->lastPais = null;

        while($row = $this->fetch($sqlCiudad, static::CONNECTION_SE_ASPIRANTE)) {
            $lastPais = $this->getLastPais($row['pais_id']);
            $lastDpto = $this->getLastDpto($row['dpto_id'], $row['pais_id']);

            $ciudad = (new Ciudad())
                ->setNombre($row['nombre'])
                ->setNId($row['id'])
                ->setNPaisId($row['pais_id'])
                ->setNDptoId($row['dpto_id'])
                ->setPais($lastPais)
                ->setDpto($lastDpto);
            $this->selPersist($ciudad);
        }
        
        foreach($this->ciudadesAdicionales as $ciudadAdicional) {
            $pais = $this->getDefaultManager()->getRepository(Pais::class)->findOneBy(['nombre' => $ciudadAdicional['pais']]);
            $dpto = $this->getDefaultManager()->getRepository(Dpto::class)->findOneBy(['nombre' => $ciudadAdicional['dpto']]);
            $ciudad = (new Ciudad())
                ->setNombre($ciudadAdicional['ciudad'])
                ->setDpto($dpto)
                ->setPais($pais);
            $this->persistAndFlush($ciudad);
            $this->progressBar->advance();
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Ciudad::class);
        $this->truncateTable(Dpto::class);
        $this->truncateTable(Pais::class);
    }


    protected function getLastPais($paisId)
    {
        if($this->lastPais === null || $paisId != $this->lastPais->getNId()) {
            $this->lastPais = $this->getDefaultManager()->getRepository(Pais::class)
                ->findOneBy(['nId' => $paisId]);
        }
        return $this->lastPais;
    }

    protected function getLastDpto($dptoId, $paisId)
    {
        if($this->lastDpto === null || $dptoId != $this->lastDpto->getNId()) {
            $this->lastDpto = $this->getDefaultManager()->getRepository(Dpto::class)
                ->findOneBy(['nId' => $dptoId, 'nPaisId' => $paisId]);
        }
        return $this->lastDpto;
    }

    protected function flushAndClear()
    {
        parent::flushAndClear();
        $this->lastPais = null;
        $this->lastDpto = null;
    }


}
