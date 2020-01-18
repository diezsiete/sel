<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\ServicioEmpleados\CertificadoLaboral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CertificadoLaboral|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoLaboral|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoLaboral[]    findAll()
 * @method CertificadoLaboral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificadoLaboralRepository extends ReportRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CertificadoLaboral::class);
    }

    /**
     * @param string $identificacion
     * @return CertificadoLaboral[]
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
     * @return CertificadoLaboral|null
     */
    public function findLastByIdentificacion($identificacion)
    {
        return $this->findByIdentificacionQuery($identificacion)
            ->orderBy('cl.fechaIngreso', 'DESC')
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
        return $this->createQueryBuilder('cl')
            ->join('cl.usuario', 'u')
            ->where('u.identificacion = :identificacion')
            ->setParameter('identificacion', $identificacion);
    }
}
