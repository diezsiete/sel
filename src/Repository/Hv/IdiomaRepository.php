<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Idioma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Idioma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Idioma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Idioma[]    findAll()
 * @method Idioma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdiomaRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Idioma::class);
    }
}
