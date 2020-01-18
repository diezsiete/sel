<?php

namespace App\Service\Novasoft\Report\Importer;

use App\Entity\Novasoft\Report\CertificadoIngresos;
use App\Repository\Novasoft\Report\CertificadoIngresosRepository;

class CertificadoIngresosImporter extends Importer
{
    /**
     * @var CertificadoIngresosRepository
     */
    private $certificadoIngresosRepo;


    /**
     * @param CertificadoIngresosRepository $certificadoIngresosRepo
     * @required
     */
    public function setCertificadoLaboralRepo(CertificadoIngresosRepository $certificadoIngresosRepo)
    {
        $this->certificadoIngresosRepo = $certificadoIngresosRepo;
    }

    /**
     * @param CertificadoIngresos $entity
     * @return CertificadoIngresos|null
     */
    protected function findEqual($entity)
    {
        $equal = $this->certificadoIngresosRepo->findEqual($entity);
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