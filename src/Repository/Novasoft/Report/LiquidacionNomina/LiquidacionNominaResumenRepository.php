<?php

namespace App\Repository\Novasoft\Report\LiquidacionNomina;

use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LiquidacionNominaResumen|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionNominaResumen|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionNominaResumen[]    findAll()
 * @method LiquidacionNominaResumen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionNominaResumenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LiquidacionNominaResumen::class);
    }

    /**
     * @param LiquidacionNominaResumen $equal
     * @return LiquidacionNominaResumen[] Returns an array of LiquidacionNominaResumen objects
     * @throws NonUniqueResultException
     */
    public function findEqual(LiquidacionNominaResumen $equal)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.convenio = :convenio')
            ->andWhere('l.fechaInicial = :fechaInicial')
            ->andWhere('l.fechaFinal = :fechaFinal')
            ->setParameter('convenio', $equal->getConvenio())
            ->setParameter('fechaInicial', $equal->getFechaInicial()->format('Y-m-d'))
            ->setParameter('fechaFinal', $equal->getFechaFinal()->format('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
