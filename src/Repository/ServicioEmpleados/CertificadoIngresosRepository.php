<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\ServicioEmpleados\CertificadoIngresos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CertificadoIngresos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoIngresos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoIngresos[]    findAll()
 * @method CertificadoIngresos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificadoIngresosRepository extends ReportRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CertificadoIngresos::class);
    }

    /**
     * @param string $identificacion
     * @return CertificadoIngresos[]
     */
    public function findByIdentificacion($identificacion)
    {
        return $this->createQueryBuilder('ci')
            ->join('ci.usuario', 'u')
            ->where('u.identificacion = :identificacion')
            ->setParameter('identificacion', $identificacion)
            ->getQuery()
            ->getResult();
    }
}
