<?php

namespace App\Repository;

use App\Entity\NivelAcademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NivelAcademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method NivelAcademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method NivelAcademico[]    findAll()
 * @method NivelAcademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelAcademicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NivelAcademico::class);
    }

    // /**
    //  * @return NivelAcademico[] Returns an array of NivelAcademico objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NivelAcademico
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
