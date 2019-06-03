<?php

namespace App\Repository;

use App\Entity\Vivienda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vivienda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vivienda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vivienda[]    findAll()
 * @method Vivienda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViviendaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vivienda::class);
    }

    // /**
    //  * @return Vivienda[] Returns an array of Vivienda objects
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
    public function findOneBySomeField($value): ?Vivienda
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
