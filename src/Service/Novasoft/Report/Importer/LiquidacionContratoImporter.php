<?php

namespace App\Service\Novasoft\Report\Importer;

use App\Entity\Novasoft\Report\CertificadoIngresos;
use App\Entity\Novasoft\Report\LiquidacionContrato;
use App\Repository\Novasoft\Report\CertificadoIngresosRepository;
use App\Repository\Novasoft\Report\LiquidacionContratoRepository;

class LiquidacionContratoImporter extends Importer
{
    /**
     * @var LiquidacionContratoRepository
     */
    private $liquidacionContratoRepo;


    /**
     * @param LiquidacionContratoRepository $liquidacionContratoRepository
     * @required
     */
    public function setCertificadoLaboralRepo(LiquidacionContratoRepository $liquidacionContratoRepository)
    {
        $this->liquidacionContratoRepo = $liquidacionContratoRepository;
    }

    /**
     * @param LiquidacionContrato $entity
     * @return LiquidacionContrato|null
     */
    protected function findEqual($entity)
    {
        $equal = $this->liquidacionContratoRepo->findEqual($entity);
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