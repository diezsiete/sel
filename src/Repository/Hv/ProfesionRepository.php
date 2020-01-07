<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Profesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Profesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profesion[]    findAll()
 * @method Profesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Profesion::class);
    }
}
