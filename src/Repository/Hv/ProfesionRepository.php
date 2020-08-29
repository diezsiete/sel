<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Profesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profesion[]    findAll()
 * @method Profesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profesion::class);
    }
}
