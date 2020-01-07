<?php

namespace App\Repository\Vacante;

use App\Entity\Vacante\VacanteArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VacanteArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacanteArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacanteArea[]    findAll()
 * @method VacanteArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanteAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VacanteArea::class);
    }
}
