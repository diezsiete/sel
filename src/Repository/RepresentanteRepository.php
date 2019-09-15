<?php

namespace App\Repository;

use App\Entity\Representante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Representante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Representante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Representante[]    findAll()
 * @method Representante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepresentanteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Representante::class);
    }

    public static function encargadoCriteria()
    {
        return Criteria::create()->where(Criteria::expr()->eq('encargado', true));
    }

    public static function bccCriteria()
    {
        return Criteria::create()->where(Criteria::expr()->eq('bcc', true));
    }

    // /**
    //  * @return Representante[] Returns an array of Representante objects
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
    public function findOneBySomeField($value): ?Representante
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
