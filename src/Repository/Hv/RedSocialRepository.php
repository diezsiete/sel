<?php

namespace App\Repository\Hv;

use App\Entity\Hv\RedSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RedSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method RedSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method RedSocial[]    findAll()
 * @method RedSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedSocialRepository extends ServiceEntityRepository
{
    use HvChildRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedSocial::class);
    }
}
