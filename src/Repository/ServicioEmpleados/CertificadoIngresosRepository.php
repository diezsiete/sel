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
class CertificadoIngresosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CertificadoIngresos::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param CertificadoIngresos $certificadoIngresos
     * @return CertificadoIngresos|null
     */
    public function findEqual(CertificadoIngresos $certificadoIngresos)
    {
        return $this->createQueryBuilder('ci')
            ->andWhere('ci.source = :source')
            ->andWhere('ci.sourceId = :sourceId')
            ->andWhere('ci.usuario = :usuario')
            ->setParameters([
                'source'   => $certificadoIngresos->getSource(),
                'sourceId' => $certificadoIngresos->getSourceId(),
                'usuario'  => $certificadoIngresos->getUsuario()
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
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
