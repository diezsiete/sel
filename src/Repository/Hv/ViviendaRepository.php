<?php

namespace App\Repository\Hv;

use App\Entity\Vivienda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vivienda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vivienda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vivienda[]    findAll()
 * @method Vivienda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViviendaRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vivienda::class);
    }
}
