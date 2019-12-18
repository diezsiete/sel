<?php


namespace App\Service\Novasoft\Report\Importer;



use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;


class LiquidacionNominaImporter extends Importer
{

    protected function importEntity($entity)
    {
        // si existe una liquidacion nomina con mismos valores borramos para sobreescribir
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
            //liquidacion nomina resumen se comparte entre multiples liquidaciones nomina. Aseguramos que no se creen duplicados
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