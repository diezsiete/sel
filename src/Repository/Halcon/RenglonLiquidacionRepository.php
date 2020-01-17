<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\RenglonLiquidacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RenglonLiquidacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method RenglonLiquidacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method RenglonLiquidacion[]    findAll()
 * @method RenglonLiquidacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RenglonLiquidacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RenglonLiquidacion::class);
    }

    // /**
    //  * @return RenglonLiquidacion[] Returns an array of RenglonLiquidacion objects
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
    public function findOneBySomeField($value): ?RenglonLiquidacion
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
