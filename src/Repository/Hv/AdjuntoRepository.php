<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Adjunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adjunto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adjunto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adjunto[]    findAll()
 * @method Adjunto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdjuntoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adjunto::class);
    }
}
