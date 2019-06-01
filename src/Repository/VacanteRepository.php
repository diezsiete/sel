<?php

namespace App\Repository;

use App\Entity\Vacante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vacante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacante[]    findAll()
 * @method Vacante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vacante::class);
    }

    // /**
    //  * @return Vacante[] Returns an array of Vacante objects
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
    public function findOneBySomeField($value): ?Vacante
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
