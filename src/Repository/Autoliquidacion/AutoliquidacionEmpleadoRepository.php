<?php

namespace App\Repository\Autoliquidacion;

use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Empleado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AutoliquidacionEmpleado|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutoliquidacionEmpleado|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutoliquidacionEmpleado[]    findAll()
 * @method AutoliquidacionEmpleado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoliquidacionEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AutoliquidacionEmpleado::class);
    }

    /**
     * @param Empleado $empleado
     * @param \DateTimeInterface $periodo
     * @return AutoliquidacionEmpleado
     */
    public function findByEmpleadoPeriodo(Empleado $empleado, \DateTimeInterface $periodo)
    {
        $qb = $this->createQueryBuilder('ae')
            ->join('ae.autoliquidacion', 'a')
            ->andWhere('ae.empleado = :empleado')
            ->andWhere('a.periodo = :periodo')
            ->setParameter('empleado', $empleado)
            ->setParameter('periodo', $periodo);
        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }


    // /**
    //  * @return AutoliquidacionEmpleado[] Returns an array of AutoliquidacionEmpleado objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AutoliquidacionEmpleado
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
