<?php

namespace App\Repository\Novasoft\Report\LiquidacionNomina;

use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNomina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LiquidacionNomina|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionNomina|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionNomina[]    findAll()
 * @method LiquidacionNomina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionNominaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiquidacionNomina::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param LiquidacionNomina $equal
     * @return LiquidacionNomina|null
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
            ->getOneOrNullResult();
    }
}
