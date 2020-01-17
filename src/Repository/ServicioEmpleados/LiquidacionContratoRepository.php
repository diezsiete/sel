<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\ServicioEmpleados\LiquidacionContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LiquidacionContrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionContrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionContrato[]    findAll()
 * @method LiquidacionContrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionContratoRepository extends ReportRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LiquidacionContrato::class);
    }


}
