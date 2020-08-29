<?php

namespace App\Repository\Novasoft\Report\LiquidacionNomina;

use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaTotal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LiquidacionNominaTotal|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionNominaTotal|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionNominaTotal[]    findAll()
 * @method LiquidacionNominaTotal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionNominaTotalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiquidacionNominaTotal::class);
    }

    // /**
    //  * @return LiquidacionNominaTotal[] Returns an array of LiquidacionNominaTotal objects
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
    public function findOneBySomeField($value): ?LiquidacionNominaTotal
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
