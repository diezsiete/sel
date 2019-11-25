<?php

namespace App\Repository\Hv;

use App\Entity\Estudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Estudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estudio[]    findAll()
 * @method Estudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Estudio::class);
    }
}
