<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ReportCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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
     * @param string $source
     * @param string|null $report
     * @return ReportCache|null|ReportCache[]|ArrayCollection
     */
    public function findLastCacheForReport(Usuario $usuario, string $source, ?string $report = null)
    {
        $qb = $this->createQueryBuilder('rc')
            ->andWhere('rc.usuario = :usuario')
            ->andWhere('rc.source = :source')
            ->setParameter('usuario', $usuario)
            ->setParameter('source', $source);
        if($report) {
            return $qb
                ->andWhere('rc.report = :report')
                ->setParameter('report', $report)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

        } else {
            return new ArrayCollection($qb
                ->getQuery()
                ->getResult());
        }
    }

    public static function filterByReportCriteria($reportName)
    {
        return Criteria::create()->where(Criteria::expr()->eq('report', $reportName));
    }
}
