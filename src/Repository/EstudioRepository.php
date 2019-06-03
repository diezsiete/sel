<?php

namespace App\Repository;

use App\Entity\Estudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Estudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estudio[]    findAll()
 * @method Estudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Estudio::class);
    }

    // /**
    //  * @return Estudio[] Returns an array of Estudio objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Estudio
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
