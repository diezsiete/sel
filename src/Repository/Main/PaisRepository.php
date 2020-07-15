<?php

namespace App\Repository\Main;

use App\Entity\Main\Pais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pais[]    findAll()
 * @method Pais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pais::class);
    }

    /**
     * @param Pais $pais
     * @return bool
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function paisHasDptos($pais)
    {
        return (bool) $this->createQueryBuilder('p')
            ->select('COUNT(d.id)')
            ->join('p.dptos', 'd')
            ->where('p = :pais')
            ->setParameter('pais', $pais)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
