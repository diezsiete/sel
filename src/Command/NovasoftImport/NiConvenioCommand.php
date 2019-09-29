<?php

namespace App\Command\NovasoftImport;

use App\Entity\Convenio;
use App\Service\Configuracion\SsrsDb;
use App\Service\ReportesServicioEmpleados;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NiConvenioCommand extends NiCommand
{
    protected static $defaultName = 'sel:ni:convenio';

    /**
     * @var ReportesServicioEmpleados
     */
    protected $reportesServicioEmpleados;

    /**
     * @required
     */
    public function setReportesServicioEmpleados(ReportesServicioEmpleados $reportesServicioEmpleados)
    {
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Actualizar convenios desde novasoft')
            ->addOption('ssrs_db', null,InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY,
                'Novasoft database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbs = $this->getSsrsDbs();
        foreach($dbs as $db) {
            $this->info("SsrsDb: " . $db->getNombre());
            $this->info(" convenios:");
            if($db->hasConvenios()) {
                $convenios = $this->reportesServicioEmpleados->setSsrsDb($db->getNombre())->getConvenios();
                foreach($convenios as $convenio) {
                    $convenio->setSsrsDb($db->getNombre());
                    $this->persistConvenio($convenio);
                }
            } else {
                $convenio = (new Convenio())
                    ->setCodigo($db->getNombre())
                    ->setSsrsDb($db->getNombre())
                    ->setNombre($db->getNombre());
                $this->persistConvenio($convenio);
            }
        }

    }

    /**
     * @return SsrsDb[]
     * @throws \Exception
     */
    protected function getSsrsDbs()
    {
        $ssrsDbOption = $this->input->getOption('ssrs_db');
        $ssrsDbs = [];
        if($ssrsDbOption) {
            foreach($ssrsDbOption as $ssrsDb) {
                $ssrsDbs[] = $this->configuracion->getSsrsDb($ssrsDb);
            }
        } else {
            $ssrsDbs = $this->configuracion->getSsrsDb();
        }
        return $ssrsDbs;
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

        $this->em->flush();
        $this->info("  [".$convenio->getCodigo()."] " . $convenio->getNombre()
            . " [" . ($persisted ? 'insert' : 'update') . "]");
        return $persisted;
    }
}
