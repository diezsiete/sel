<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Referencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Referencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referencia[]    findAll()
 * @method Referencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Referencia::class);
    }
}
