<?php

namespace App\Repository;

use App\Entity\ReporteNomina;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReporteNomina|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReporteNomina|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReporteNomina[]    findAll()
 * @method ReporteNomina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReporteNominaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReporteNomina::class);
    }

     /**
      * @return ReporteNomina|null Returns an array of ReporteNomina objects
      */
    public function findByFecha(Usuario $usuario, \DateTimeInterface $fecha)
    {
        $from = new \DateTime($fecha->format("Y-m-d")." 00:00:00");
        $to   = new \DateTime($fecha->format("Y-m-d")." 23:59:59");

        return $this->createQueryBuilder('rn')
            ->where('rn.usuario = :usuario')
            ->setParameter('usuario', $usuario)
            ->andWhere('rn.fecha BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?ReporteNomina
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
