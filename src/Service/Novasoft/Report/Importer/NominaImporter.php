<?php


namespace App\Service\Novasoft\Report\Importer;


use App\Entity\Novasoft\Report\Nomina\Nomina;
use App\Repository\Novasoft\Report\Nomina\NominaRepository;

class NominaImporter extends Importer
{
    /**
     * @var NominaRepository
     */
    private $nominaRepo;

    /**
     * @param NominaRepository $nominaRepo
     * @required
     */
    public function setNominaRepo(NominaRepository $nominaRepo)
    {
        $this->nominaRepo = $nominaRepo;
    }

    /**
     * @param Nomina $entity
     * @return Nomina|null
     */
    protected function findEqual($entity)
    {
        return $this->nominaRepo->findByFecha($entity->getUsuario(), $entity->getFecha());
    }

    /**
     * Que hacer si findEqual encontro coincidencia exacta en base de datos
     * @param $equal
     * @return bool determina si importar o no
     */
    protected function handleEqual($equal): bool
    {
        $this->em->remove($equal);
        $this->em->flush();
        return true;
    }

    /**
     * @param Nomina $entity
     * @param string $action
     */
    protected function logImportEntity($entity, $action)
    {
        $this->info(sprintf("%s %s", $entity->getFecha()->format('Y-m'), $action));
    }
}