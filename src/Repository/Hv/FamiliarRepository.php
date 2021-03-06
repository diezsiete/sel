<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Familiar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Familiar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Familiar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Familiar[]    findAll()
 * @method Familiar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamiliarRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Familiar::class);
    }
}
