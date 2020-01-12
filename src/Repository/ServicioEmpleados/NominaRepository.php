<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\ServicioEmpleados\Nomina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Nomina|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nomina|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nomina[]    findAll()
 * @method Nomina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NominaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Nomina::class);
    }

    // /**
    //  * @return Nomina[] Returns an array of Nomina objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nomina
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
