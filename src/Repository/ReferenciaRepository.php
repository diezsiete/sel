<?php

namespace App\Repository;

use App\Entity\Referencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Referencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referencia[]    findAll()
 * @method Referencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Referencia::class);
    }

    // /**
    //  * @return Referencia[] Returns an array of Referencia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Referencia
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
