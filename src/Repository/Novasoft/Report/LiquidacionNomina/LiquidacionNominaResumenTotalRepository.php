<?php

namespace App\Repository\Novasoft\Report\LiquidacionNomina;

use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenTotal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LiquidacionNominaResumenTotal|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionNominaResumenTotal|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionNominaResumenTotal[]    findAll()
 * @method LiquidacionNominaResumenTotal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionNominaResumenTotalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiquidacionNominaResumenTotal::class);
    }

    // /**
    //  * @return LiquidacionNominaResumenTotal[] Returns an array of LiquidacionNominaResumenTotal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LiquidacionNominaResumenTotal
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
