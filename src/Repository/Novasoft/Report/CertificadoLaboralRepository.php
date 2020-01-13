<?php

namespace App\Repository\Novasoft\Report;

use App\Entity\Novasoft\Report\CertificadoLaboral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CertificadoLaboral|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoLaboral|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoLaboral[]    findAll()
 * @method CertificadoLaboral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificadoLaboralRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CertificadoLaboral::class);
    }

    // /**
    //  * @return CertificadoLaboral[] Returns an array of CertificadoLaboral objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CertificadoLaboral
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
