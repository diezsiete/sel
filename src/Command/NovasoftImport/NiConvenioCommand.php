<?php

namespace App\Command\NovasoftImport;

use App\Entity\Convenio;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NiConvenioCommand extends NiCommand
{
    protected static $defaultName = 'ni:convenio';


    protected function configure()
    {
        parent::configure();
        $this->setDescription('Actualizar convenios desde novasoft');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $dbs = $this->getSsrsDbs();
            foreach($dbs as $db) {
                $this->io->writeln("SsrsDb: " . $db->getNombre());
                $this->io->writeln(" convenios:");
                if($db->hasConvenios()) {
                    $convenios = $this->reportesServicioEmpleados->setSsrsDb($db)->getConvenios();
                    foreach($convenios as $convenio) {
                        $convenio->setSsrsDb($db->getNombre());
                        $persisted = $this->persistConvenio($convenio);
                        $this->em->flush();
                        $this->io->writeln("  " . $convenio->getNombre() . " [" . ($persisted ? 'insert' : 'update') . "]");
                    }
                } else {
                    $this->io->writeln("  no tiene");
                }
            }
        } catch (\Exception $e) {
            $this->io->error($e->getMessage());
        }
    }

    /**
     * @param Convenio $convenio
     * @return bool
     */
    private function persistConvenio(Convenio $convenio)
    {
        $persisted = true;
        $convenioDb = $this->em->getRepository(Convenio::class)->find($convenio->getCodigo());
        if($convenioDb) {
            $persisted = false;
            $convenioDb
                ->setNombre($convenio->getNombre())
                ->setCodigo($convenio->getCodigo())
                ->setCodigoCliente($convenio->getCodigoCliente())
                ->setDireccion($convenio->getDireccion());
        } else {
            $this->em->persist($convenio);
        }
        return $persisted;
    }
}
