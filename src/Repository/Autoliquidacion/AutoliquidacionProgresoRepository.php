<?php

namespace App\Repository\Autoliquidacion;

use App\Entity\Autoliquidacion\AutoliquidacionProgreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AutoliquidacionProgreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutoliquidacionProgreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutoliquidacionProgreso[]    findAll()
 * @method AutoliquidacionProgreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoliquidacionProgresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AutoliquidacionProgreso::class);
    }

    // /**
    //  * @return AutoliquidacionProgreso[] Returns an array of AutoliquidacionProgreso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AutoliquidacionProgreso
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
