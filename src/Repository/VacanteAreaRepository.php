<?php

namespace App\Repository;

use App\Entity\VacanteArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VacanteArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacanteArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacanteArea[]    findAll()
 * @method VacanteArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanteAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VacanteArea::class);
    }

    // /**
    //  * @return VacanteArea[] Returns an array of VacanteArea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VacanteArea
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
