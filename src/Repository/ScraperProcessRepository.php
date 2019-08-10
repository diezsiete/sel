<?php

namespace App\Repository;

use App\Entity\ScraperProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScraperProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScraperProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScraperProcess[]    findAll()
 * @method ScraperProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScraperProcessRepository extends ServiceEntityRepository
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
