<?php

namespace App\Repository;

use App\Entity\EstudioInstituto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstudioInstituto|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstudioInstituto|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstudioInstituto[]    findAll()
 * @method EstudioInstituto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioInstitutoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstudioInstituto::class);
    }

    // /**
    //  * @return EstudioInstituto[] Returns an array of EstudioInstituto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstudioInstituto
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
