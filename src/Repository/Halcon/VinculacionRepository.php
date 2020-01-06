<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\Vinculacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vinculacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vinculacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vinculacion[]    findAll()
 * @method Vinculacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinculacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vinculacion::class);
    }

    // /**
    //  * @return Vinculacion[] Returns an array of Vinculacion objects
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
    public function findOneBySomeField($value): ?Vinculacion
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
