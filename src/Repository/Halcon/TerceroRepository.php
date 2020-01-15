<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\Tercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tercero|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tercero|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tercero[]    findAll()
 * @method Tercero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerceroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tercero::class);
    }

    // /**
    //  * @return Tercero[] Returns an array of Tercero objects
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
    public function findOneBySomeField($value): ?Tercero
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
