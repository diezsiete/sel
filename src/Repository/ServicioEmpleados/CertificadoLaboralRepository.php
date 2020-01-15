<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\ServicioEmpleados\CertificadoLaboral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CertificadoLaboral|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoLaboral|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoLaboral[]    findAll()
 * @method CertificadoLaboral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificadoLaboralRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CertificadoLaboral::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param CertificadoLaboral $certificadoLaboral
     * @return CertificadoLaboral|null
     */
    public function findEqual(CertificadoLaboral $certificadoLaboral)
    {
        return $this->createQueryBuilder('cl')
            ->andWhere('cl.source = :source')
            ->andWhere('cl.sourceId = :sourceId')
            ->andWhere('cl.usuario = :usuario')
            ->setParameters([
                'source'   => $certificadoLaboral->getSource(),
                'sourceId' => $certificadoLaboral->getSourceId(),
                'usuario'  => $certificadoLaboral->getUsuario()
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $identificacion
     * @return CertificadoLaboral[]
     */
    public function findByIdentificacion($identificacion)
    {
        return $this->createQueryBuilder('cl')
            ->join('cl.usuario', 'u')
            ->where('u.identificacion = :identificacion')
            ->setParameter('identificacion', $identificacion)
            ->getQuery()
            ->getResult();
    }
}
