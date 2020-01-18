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
     * @noinspection PhpDocMissingThrowsInspection
     * @param $identificacion
     * @param null $noContrato
     * @param null $liqDefini
     * @return CabezaLiquidacion[]|CabezaLiquidacion
     */
    public function findLiquidacionContrato($identificacion, $noContrato = null, $liqDefini = null)
    {
        $qb = $this->createQueryBuilder('cl')
            ->andWhere("REPLACE(cl.auxiliar, '.', '') = :identificacion")
            ->setParameter('identificacion', $identificacion);
        if(!$noContrato) {
            return $qb
                ->getQuery()
                ->getResult();
        } else {
            return $qb
                ->join('cl.vinculacion', 'v')
                ->andWhere('v.noContrat = :noContrato')
                ->andWhere('cl.liqDefini = :liqDefini')
                ->setParameter('noContrato', $noContrato)
                ->setParameter('liqDefini', $liqDefini)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
    }


}
