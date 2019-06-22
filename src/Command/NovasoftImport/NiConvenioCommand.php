<?php

namespace App\Command\NovasoftImport;

use App\Service\Configuracion\Configuracion;
use App\Service\NovasoftImport\ConvenioImport;
use App\Service\ReportesServicioEmpleados;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NiConvenioCommand extends NiCommand
{
    protected static $defaultName = 'ni:convenio';

    /**
     * @var ConvenioImport
     */
    private $convenioImport;

    /**
     * @var ReportesServicioEmpleados
     */
    private $reportesServicioEmpleados;

    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(ConvenioImport $convenioImport, ReportesServicioEmpleados $reportesServicioEmpleados, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->convenioImport = $convenioImport;
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
        $this->em = $em;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Actualizar convenios desde novasoft');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $dbs = $this->getSsrsDbs();
            foreach($dbs as $db) {
                $io->writeln("SsrsDb: " . $db->getNombre());
                $io->writeln(" convenios:");
                if($db->hasConvenios()) {
                    $convenios = $this->reportesServicioEmpleados->setSsrsDb($db)->getConvenios();
                    foreach($convenios as $convenio) {
                        $convenio->setSsrsDb($db->getNombre());
                        $persisted = $this->convenioImport->persistConvenio($convenio);
                        $this->em->flush();
                        $io->writeln("  " . $convenio->getNombre() . " [" . ($persisted ? 'insert' : 'update') . "]");
                    }
                } else {
                    $io->writeln("  no tiene");
                }
            }
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
