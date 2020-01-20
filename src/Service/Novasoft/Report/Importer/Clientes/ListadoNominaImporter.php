<?php


namespace App\Service\Novasoft\Report\Importer\Clientes;


use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina;
use App\Repository\Novasoft\Report\Clientes\ListadoNominaRepository;

class ListadoNominaImporter extends Importer
{
    /**
     * @var ListadoNominaRepository
     */
    private $listadoNominaRepo;

    /**
     * @param ListadoNominaRepository $listadoNominaRepo
     * @required
     */
    public function setCertificadoLaboralRepo(ListadoNominaRepository $listadoNominaRepo)
    {
        $this->listadoNominaRepo = $listadoNominaRepo;
    }

    /**
     * @param ListadoNomina $entity
     * @return
     */
    protected function findEqual($entity)
    {
        $equal = $this->listadoNominaRepo->findEqual($entity);
        return $equal;
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
}