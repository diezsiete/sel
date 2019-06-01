<?php

namespace App\Repository;

use App\Entity\VacanteRedSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VacanteRedSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacanteRedSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacanteRedSocial[]    findAll()
 * @method VacanteRedSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanteRedSocialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VacanteRedSocial::class);
    }

    // /**
    //  * @return VacanteRedSocial[] Returns an array of VacanteRedSocial objects
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
    public function findOneBySomeField($value): ?VacanteRedSocial
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
