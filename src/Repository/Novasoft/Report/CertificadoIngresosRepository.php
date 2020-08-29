<?php

namespace App\Repository\Novasoft\Report;

use App\Entity\Novasoft\Report\CertificadoIngresos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CertificadoIngresos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoIngresos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoIngresos[]    findAll()
 * @method CertificadoIngresos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @deprecated
 */
class CertificadoIngresosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CertificadoIngresos::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param CertificadoIngresos $entity
     * @return mixed
     */
    public function findEqual(CertificadoIngresos $entity)
    {
        return $this->createQueryBuilder('ci')
            ->andWhere('ci.usuario = :usuario')
            ->andWhere('ci.periodoCertificacionDe = :de')
            ->andWhere('ci.periodoCertificacionA = :a')
            ->setParameter('usuario', $entity->getUsuario())
            ->setParameter('de', $entity->getPeriodoCertificacionDe()->format('Y-m-d'))
            ->setParameter('a', $entity->getPeriodoCertificacionA()->format('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
