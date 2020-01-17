<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ReportCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReportCache|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportCache|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportCache[]    findAll()
 * @method ReportCache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportCacheRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReportCache::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param Usuario $usuario
     * @param string $report
     * @return ReportCache|null
     */
    public function findLastCacheForReport(Usuario $usuario, string $source, string $report)
    {
        return $this->createQueryBuilder('rc')
            ->andWhere('rc.usuario = :usuario')
            ->andWhere('rc.source = :source')
            ->andWhere('rc.report = :report')
            ->setMaxResults(1)
            ->setParameter('usuario', $usuario)
            ->setParameter('source', $source)
            ->setParameter('report', $report)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return ReportCache[] Returns an array of ReportCache objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportCache
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
