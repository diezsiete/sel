<?php

namespace App\Repository\Novasoft\Report\LiquidacionNomina;

use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumenRenglon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LiquidacionNominaResumenRenglon|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionNominaResumenRenglon|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionNominaResumenRenglon[]    findAll()
 * @method LiquidacionNominaResumenRenglon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionNominaResumenRenglonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiquidacionNominaResumenRenglon::class);
    }

    // /**
    //  * @return LiquidacionNominaResumenRenglon[] Returns an array of LiquidacionNominaResumenRenglon objects
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
    public function findOneBySomeField($value): ?LiquidacionNominaResumenRenglon
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
