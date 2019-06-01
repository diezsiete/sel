<?php

namespace App\Repository;

use App\Entity\Ciudad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ciudad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ciudad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ciudad[]    findAll()
 * @method Ciudad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiudadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ciudad::class);
    }

    /**
     * @return Ciudad[]
     */
    public function findColmbia()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.pais = :colombia')
            ->setParameter('colombia', '057')
            ->getQuery()
            ->getResult();
    }

    public function ciudadesColombiaCriteria()
    {
        return Criteria::create()->andWhere(Criteria::expr()->eq('c.pais', '057'));
    }

    /*
    public function findOneBySomeField($value): ?Ciudad
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
