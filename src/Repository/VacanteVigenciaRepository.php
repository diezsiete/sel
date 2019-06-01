<?php

namespace App\Repository;

use App\Entity\VacanteVigencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VacanteVigencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacanteVigencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacanteVigencia[]    findAll()
 * @method VacanteVigencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanteVigenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VacanteVigencia::class);
    }

    // /**
    //  * @return VacanteVigencia[] Returns an array of VacanteVigencia objects
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
    public function findOneBySomeField($value): ?VacanteVigencia
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
