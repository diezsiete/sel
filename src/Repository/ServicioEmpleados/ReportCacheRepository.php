<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\ReportCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportCache|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportCache|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportCache[]    findAll()
 * @method ReportCache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportCacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param string $source
     * @param string|null $identOrId
     * @return Usuario|Usuario[]|null
     */
    public function findUsuariosBySource(string $source, ?string $identOrId = null)
    {
        $query = $this->findUsuariosBySourceQuery($source, $identOrId);
        return $identOrId ? $query->getOneOrNullResult() : $query->getResult();
    }

    /**
     * @param string $source
     * @param string|null $identOrId
     * @return Query
     */
    public function findUsuariosBySourceQuery(string $source, ?string $identOrId = null)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('u')
            ->from(Usuario::class, 'u')
            ->join($this->_entityName, 'rc', 'WITH', 'u = rc.usuario')
            ->where('rc.source = :source')
            ->setParameter('source', $source)
            ->groupBy('u.id');

        if($identOrId) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->eq('u.id', ':identOrId'),
                $qb->expr()->eq('u.identificacion', ':identOrId')
            ))
                ->setParameter('identOrId', $identOrId);
        }

        return $qb->getQuery();
    }

    public function findUsuariosWithNoCacheQuery(string $rol, $count = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb ->select($count ? 'COUNT(u.id)' : 'u')
            ->from(Usuario::class, 'u')
            ->leftJoin($this->_entityName, 'rc', 'WITH', 'u = rc.usuario')
            ->where('rc.id IS NULL')
            ->andWhere($qb->expr()->like('u.roles', ':rol'))
            ->setParameter('rol', "%$rol%");
        return $qb->getQuery();
    }

    public static function filterByReportCriteria($reportName)
    {
        return Criteria::create()->where(Criteria::expr()->eq('report', $reportName));
    }
}
