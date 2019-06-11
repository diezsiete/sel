<?php

namespace App\Repository;

use App\Entity\Hv;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Hv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hv[]    findAll()
 * @method Hv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HvRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Hv::class);
    }

    public function findByUsuario($usuario)
    {
        return $this->findOneBy(['usuario' => $usuario]);
    }

    // /**
    //  * @return Hv[] Returns an array of Hv objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hv
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
