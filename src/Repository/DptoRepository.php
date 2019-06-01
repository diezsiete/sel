<?php

namespace App\Repository;

use App\Entity\Dpto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dpto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dpto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dpto[]    findAll()
 * @method Dpto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dpto::class);
    }

    // /**
    //  * @return Dpto[] Returns an array of Dpto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dpto
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
