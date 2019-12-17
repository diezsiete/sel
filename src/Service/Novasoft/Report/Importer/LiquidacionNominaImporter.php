<?php


namespace App\Service\Novasoft\Report\Importer;



use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;


class LiquidacionNominaImporter extends Importer
{

    protected function importEntity($entity)
    {
        /** @var LiquidacionNomina $equal */
        $equal = $this->em->getRepository(get_class($entity))->findEqual($entity);
        if($equal) {
            $this->em->remove($equal->getResumen());
            $this->em->flush();
        }
        parent::importEntity($entity);
    }

    protected function handleManyToOne($entity, $parent, $mapping)
    {
        if($mapping['targetEntity'] === LiquidacionNominaResumen::class) {
            //TODO por ahora buscamos manualmente
            //seria ideal que la primaria fuera convenio, fechaInicial y fechaFinal e Importer manejara automaticamente
            /** @var LiquidacionNominaResumen $parent */
            $parentDb = $this->em->getRepository(get_class($parent))->findEqual($parent);
            if (!$parentDb) {
                $this->importEntity($parent);
                $parentDb = $parent;
            }
            return $parentDb;
        } else {
            parent::handleManyToOne($entity, $parent, $mapping);
        }
    }
}