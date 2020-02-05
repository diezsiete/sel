<?php

namespace App\Repository\Main;

use App\Entity\Main\Dpto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dpto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dpto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dpto[]    findAll()
 * @method Dpto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dpto::class);
    }

    /**
     * @param Dpto $dpto
     * @return bool
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function dptoHasCiudades($dpto)
    {
        return (bool) $this->createQueryBuilder('d')
            ->select('COUNT(c.id)')
            ->join('d.ciudades', 'c')
            ->where('d = :dpto')
            ->setParameter('dpto', $dpto)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
