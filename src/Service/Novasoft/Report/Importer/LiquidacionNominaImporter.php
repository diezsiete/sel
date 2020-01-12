<?php
namespace App\Service\Novasoft\Report\Importer;

use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;
use App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;


class LiquidacionNominaImporter extends Importer
{
    /**
     * @var LiquidacionNominaRepository
     */
    private $liquidacionNominaRepo;

    /**
     * @param LiquidacionNominaRepository $liquidacionNominaRepo
     * @required
     */
    public function setLiquidacionNominaRepository(LiquidacionNominaRepository $liquidacionNominaRepo)
    {
        $this->liquidacionNominaRepo = $liquidacionNominaRepo;
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

    /**
     * @param LiquidacionNomina $entity
     * @return LiquidacionNomina|null
     */
    protected function findEqual($entity)
    {
        return $this->liquidacionNominaRepo->findEqual($entity);
    }

    /**
     * @param LiquidacionNomina $equal
     * @return bool
     */
    protected function handleEqual($equal): bool
    {
        $this->em->remove($equal->getResumen());
        $this->em->flush();
        return true;
    }
}