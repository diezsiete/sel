<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Vivienda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vivienda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vivienda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vivienda[]    findAll()
 * @method Vivienda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViviendaRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vivienda::class);
    }
}
