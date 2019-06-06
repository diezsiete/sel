<?php

namespace App\Command\Migration;

use App\Entity\Ciudad;
use App\Entity\Dpto;
use App\Entity\Pais;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationCiudadCommand extends MigrationCommand
{
    protected static $defaultName = 'migration:ciudad';

    protected function configure()
    {
        $this
            ->setDescription('Migracion de pais, dpto y ciudad')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = "SELECT * FROM `pais`";

        $se = $this->getSeConnection();

        $progressBar = $this->initProgressBar($this->countSql($sql));

        $stmt = $se->query($sql);
        while ($row = $stmt->fetch()) {
            $pais = (new Pais())
                ->setNombre($row['nombre'])
                ->setNId($row['id']);

            $this->persistAndFlush($pais);

            $sqlDpto = "SELECT * FROM `dpto` WHERE pais_id = '" . $pais->getNId(). "'";
            $stmtDpto = $se->query($sqlDpto);
            while($rowDpto = $stmtDpto->fetch()) {
                $dpto = (new Dpto())
                    ->setNombre($rowDpto['nombre'])
                    ->setNId($rowDpto['id'])
                    ->setNPaisId($rowDpto['pais_id'])
                    ->setPais($pais);

                $this->persistAndFlush($dpto);

                $sqlCiudad = "SELECT * FROM `ciudad` WHERE pais_id = '". $dpto->getNPaisId()
                           . "' AND dpto_id = '" . $dpto->getNId() . "'";
                $stmtCiudad = $se->query($sqlCiudad);
                while($rowCiudad = $stmtCiudad->fetch()) {
                    $ciudad = (new Ciudad())
                        ->setNombre($rowCiudad['nombre'])
                        ->setNId($rowCiudad['id'])
                        ->setNPaisId($rowCiudad['pais_id'])
                        ->setNDptoId($rowCiudad['dpto_id'])
                        ->setPais($pais)
                        ->setDpto($dpto);

                    $this->persistAndFlush($ciudad);
                }
            }

            $progressBar->advance();
        }
    }


}
