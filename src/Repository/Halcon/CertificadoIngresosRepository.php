<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\CertificadoIngresos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CertificadoIngresos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoIngresos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoIngresos[]    findAll()
 * @method CertificadoIngresos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificadoIngresosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CertificadoIngresos::class);
    }

    /**
     * @param $identificacion
     * @return CertificadoIngresos[]
     */
    public function findByIdentificacion($identificacion)
    {
        return $this->findByIdentificacionQuery($identificacion)
            ->getQuery()
            ->getResult();
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param $identificacion
     * @return int
     */
    public function countByIdentificacion($identificacion)
    {
        return (int)$this->findByIdentificacionQuery($identificacion)
            ->select('COUNT(ci.nitTercer)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $identificacion
     * @return QueryBuilder
     */
    private function findByIdentificacionQuery($identificacion)
    {
        $qb = $this->createQueryBuilder('ci');

        if(!$identificacion) {
            $qb->where('ci.nitTercer != 0');
        }
        else if(!is_array($identificacion)) {
            $qb->where('ci.nitTercer = :identificacion')
                ->setParameter('identificacion', $identificacion);
        } else {
            $qb->where($qb->expr()->in('ci.nitTercer', $identificacion));
        }

        return $qb;
    }
}
