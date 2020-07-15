<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\PagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PagoDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method PagoDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method PagoDetalle[]    findAll()
 * @method PagoDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PagoDetalle::class);
    }

    // /**
    //  * @return PagoDetalle[] Returns an array of PagoDetalle objects
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
    public function findOneBySomeField($value): ?PagoDetalle
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
