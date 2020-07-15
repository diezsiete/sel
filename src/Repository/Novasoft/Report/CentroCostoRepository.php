<?php

namespace App\Repository\Novasoft\Report;

use App\Entity\Novasoft\Report\CentroCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CentroCosto|null find($id, $lockMode = null, $lockVersion = null)
 * @method CentroCosto|null findOneBy(array $criteria, array $orderBy = null)
 * @method CentroCosto[]    findAll()
 * @method CentroCosto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentroCostoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CentroCosto::class);
    }

    // /**
    //  * @return CentroCosto[] Returns an array of CentroCosto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CentroCosto
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
