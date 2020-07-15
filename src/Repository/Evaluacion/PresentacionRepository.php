<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\Presentacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Presentacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Presentacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Presentacion[]    findAll()
 * @method Presentacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresentacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Presentacion::class);
    }

    // /**
    //  * @return Presentacion[] Returns an array of Presentacion objects
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
    public function findOneBySomeField($value): ?Presentacion
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
