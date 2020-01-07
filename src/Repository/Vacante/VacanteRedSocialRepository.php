<?php

namespace App\Repository\Vacante;

use App\Entity\Vacante\VacanteRedSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VacanteRedSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacanteRedSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacanteRedSocial[]    findAll()
 * @method VacanteRedSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanteRedSocialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VacanteRedSocial::class);
    }
}
