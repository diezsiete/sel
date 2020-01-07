<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Experiencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Experiencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Experiencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Experiencia[]    findAll()
 * @method Experiencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperienciaRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Experiencia::class);
    }
}
