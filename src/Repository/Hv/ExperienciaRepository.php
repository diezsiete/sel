<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Experiencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Experiencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Experiencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Experiencia[]    findAll()
 * @method Experiencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperienciaRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experiencia::class);
    }
}
