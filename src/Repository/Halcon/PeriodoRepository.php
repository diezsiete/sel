<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\Periodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Periodo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periodo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periodo[]    findAll()
 * @method Periodo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Periodo::class);
    }

    // /**
    //  * @return Periodo[] Returns an array of Periodo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Periodo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
