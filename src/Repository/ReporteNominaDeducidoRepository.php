<?php

namespace App\Repository;

use App\Entity\ReporteNominaDeducido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReporteNominaDeducido|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReporteNominaDeducido|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReporteNominaDeducido[]    findAll()
 * @method ReporteNominaDeducido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReporteNominaDeducidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReporteNominaDeducido::class);
    }

    // /**
    //  * @return ReporteNominaDeducido[] Returns an array of ReporteNominaDeducido objects
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
    public function findOneBySomeField($value): ?ReporteNominaDeducido
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
