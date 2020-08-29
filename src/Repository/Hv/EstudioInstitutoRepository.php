<?php

namespace App\Repository\Hv;

use App\Entity\Hv\EstudioInstituto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EstudioInstituto|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstudioInstituto|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstudioInstituto[]    findAll()
 * @method EstudioInstituto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudioInstitutoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstudioInstituto::class);
    }
}
