<?php

namespace App\Repository;

use App\Entity\ReporteNominaDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReporteNominaDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReporteNominaDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReporteNominaDetalle[]    findAll()
 * @method ReporteNominaDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReporteNominaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReporteNominaDetalle::class);
    }

    // /**
    //  * @return ReporteNominaDetalle[] Returns an array of ReporteNominaDetalle objects
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
    public function findOneBySomeField($value): ?ReporteNominaDetalle
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
