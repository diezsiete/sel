<?php

namespace App\Repository;

use App\Entity\ScrapperProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScrapperProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScrapperProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScrapperProcess[]    findAll()
 * @method ScrapperProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScrapperProcessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScrapperResponse::class);
    }

    // /**
    //  * @return ScrapperResponse[] Returns an array of ScrapperResponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScrapperResponse
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
