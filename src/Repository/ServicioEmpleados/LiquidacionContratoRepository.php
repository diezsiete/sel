<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\ServicioEmpleados\LiquidacionContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LiquidacionContrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionContrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionContrato[]    findAll()
 * @method LiquidacionContrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LiquidacionContrato::class);
    }

    // /**
    //  * @return LiquidacionContrato[] Returns an array of LiquidacionContrato objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LiquidacionContrato
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
