<?php

namespace App\Repository\Hv;

use App\Entity\Hv\EstudioCodigo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EstudioCodigo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstudioCodigo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstudioCodigo[]    findAll()
 * @method EstudioCodigo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioCodigoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstudioCodigo::class);
    }

}
