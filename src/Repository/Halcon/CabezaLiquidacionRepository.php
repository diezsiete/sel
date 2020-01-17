<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\CabezaLiquidacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CabezaLiquidacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CabezaLiquidacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CabezaLiquidacion[]    findAll()
 * @method CabezaLiquidacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CabezaLiquidacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CabezaLiquidacion::class);
    }

    /**
     * @param $identificacion
     * @return CabezaLiquidacion[]
     */
    public function findLiquidacionContrato($identificacion)
    {
        return $this->createQueryBuilder('cl')
            ->andWhere("REPLACE(cl.auxiliar, '.', '') = :identificacion")
            ->setParameter('identificacion', $identificacion)
            ->getQuery()
            ->getResult();
    }


}
