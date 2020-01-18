<?php


namespace App\Service\Novasoft\Report\Importer;


use App\Entity\Novasoft\Report\CertificadoLaboral;
use App\Repository\Novasoft\Report\CertificadoLaboralRepository;

class CertificadoLaboralImporter extends Importer
{

    /**
     * @var CertificadoLaboralRepository
     */
    private $certificadoLaboralRepo;

    /**
     * @param CertificadoLaboralRepository $certificadoLaboralRepo
     * @required
     */
    public function setCertificadoLaboralRepo(CertificadoLaboralRepository $certificadoLaboralRepo)
    {
        $this->certificadoLaboralRepo = $certificadoLaboralRepo;
    }

    /**
     * @param CertificadoLaboral $entity
     * @return CertificadoLaboral|null
     */
    protected function findEqual($entity)
    {
        $equal = $this->certificadoLaboralRepo->findEqual($entity);
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