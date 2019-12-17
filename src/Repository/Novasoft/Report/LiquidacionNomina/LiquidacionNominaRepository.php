<?php

namespace App\Repository\Novasoft\Report\LiquidacionNomina;

use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LiquidacionNomina|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionNomina|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionNomina[]    findAll()
 * @method LiquidacionNomina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionNominaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LiquidacionNomina::class);
    }

    /**
     * @param LiquidacionNomina $equal
     * @return LiquidacionNomina[] Returns an array of LiquidacionNominaResumen objects
     * @throws NonUniqueResultException
     */
    public function findEqual(LiquidacionNomina $equal)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.convenio = :convenio')
            ->andWhere('l.empleado = :empleado')
            ->andWhere('l.fechaInicial = :fechaInicial')
            ->andWhere('l.fechaFinal = :fechaFinal')
            ->setParameter('convenio', $equal->getConvenio())
            ->setParameter('empleado', $equal->getEmpleado())
            ->setParameter('fechaInicial', $equal->getFechaInicial()->format('Y-m-d'))
            ->setParameter('fechaFinal', $equal->getFechaFinal()->format('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
