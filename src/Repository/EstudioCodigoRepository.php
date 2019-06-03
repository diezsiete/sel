<?php

namespace App\Repository;

use App\Entity\EstudioCodigo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstudioCodigo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstudioCodigo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstudioCodigo[]    findAll()
 * @method EstudioCodigo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioCodigoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstudioCodigo::class);
    }

    // /**
    //  * @return EstudioCodigo[] Returns an array of EstudioCodigo objects
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
    public function findOneBySomeField($value): ?EstudioCodigo
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
