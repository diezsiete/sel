<?php

namespace App\Repository;

use App\Entity\HvAdjunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HvAdjunto|null find($id, $lockMode = null, $lockVersion = null)
 * @method HvAdjunto|null findOneBy(array $criteria, array $orderBy = null)
 * @method HvAdjunto[]    findAll()
 * @method HvAdjunto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HvAdjuntoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HvAdjunto::class);
    }

    // /**
    //  * @return HvAdjunto[] Returns an array of HvAdjunto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HvAdjunto
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
