<?php

namespace App\Repository\Novasoft\Report\Nomina;

use App\Entity\Novasoft\Report\Nomina\NominaDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NominaDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method NominaDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method NominaDetalle[]    findAll()
 * @method NominaDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NominaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NominaDetalle::class);
    }
}
