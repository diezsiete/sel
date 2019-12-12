<?php

namespace App\Repository\Novasoft\Report;

use App\Entity\Novasoft\Report\TrabajadorActivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TrabajadorActivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrabajadorActivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrabajadorActivo[]    findAll()
 * @method TrabajadorActivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrabajadorActivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrabajadorActivo::class);
    }

    // /**
    //  * @return TrabajadorActivo[] Returns an array of TrabajadorActivo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrabajadorActivo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
