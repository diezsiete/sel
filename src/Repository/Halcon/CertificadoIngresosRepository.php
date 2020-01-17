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
            ->select('COUNT(ci.noContrat)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCertificado($identificacion, $usuario, $noContrat, $ano)
    {
        return $this->findByIdentificacionQuery($identificacion)
            ->join('ci.empresa', 'empresa')
            ->andWhere('empresa.usuario = :usuario')
            ->andWhere('ci.noContrat = :noContrat')
            ->andWhere('ci.ano = :ano')
            ->setParameter('usuario', $usuario)
            ->setParameter('noContrat', $noContrat)
            ->setParameter('ano', $ano)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $identificacion
     * @return QueryBuilder
     */
    private function findByIdentificacionQuery($identificacion)
    {
        $qb = $this->createQueryBuilder('ci')
            ->join('ci.tercero', 't');

        if(!$identificacion) {
            $qb->where('t.nitTercer != 0');
        }
        else if(!is_array($identificacion)) {
            $qb->where('t.nitTercer = :identificacion')
                ->setParameter('identificacion', $identificacion);
        } else {
            $qb->where($qb->expr()->in('t.nitTercer', $identificacion));
        }

        return $qb;
    }
}
