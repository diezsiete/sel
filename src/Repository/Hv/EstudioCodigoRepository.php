<?php

namespace App\Repository\Hv;

use App\Entity\Hv\EstudioCodigo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstudioCodigo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstudioCodigo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstudioCodigo[]    findAll()
 * @method EstudioCodigo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioCodigoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstudioCodigo::class);
    }

}
