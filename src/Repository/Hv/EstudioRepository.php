<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Estudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Estudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estudio[]    findAll()
 * @method Estudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estudio::class);
    }
}
