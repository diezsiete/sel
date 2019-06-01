<?php

namespace App\Repository;

use App\Entity\LicenciaConduccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LicenciaConduccion|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicenciaConduccion|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicenciaConduccion[]    findAll()
 * @method LicenciaConduccion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenciaConduccionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LicenciaConduccion::class);
    }

    // /**
    //  * @return LicenciaConduccion[] Returns an array of LicenciaConduccion objects
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
    public function findOneBySomeField($value): ?LicenciaConduccion
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
