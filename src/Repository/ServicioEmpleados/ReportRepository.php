<?php


namespace App\Repository\ServicioEmpleados;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class ReportRepository extends ServiceEntityRepository
{
    public function findBySourceId($source, $identificacion, $sourceId)
    {
        return $this->createQueryBuilder('report')
            ->join('report.usuario', 'u')
            ->andWhere('report.source = :source')
            ->andWhere('u.identificacion = :identificacion')
            ->andWhere('report.sourceId = :sourceId')
            ->setParameters([
                'source' => $source,
                'identificacion' => $identificacion,
                'sourceId' => $sourceId
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}