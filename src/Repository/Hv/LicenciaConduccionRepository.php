<?php

namespace App\Repository\Hv;

use App\Entity\Hv\LicenciaConduccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LicenciaConduccion|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicenciaConduccion|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicenciaConduccion[]    findAll()
 * @method LicenciaConduccion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenciaConduccionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LicenciaConduccion::class);
    }
}
