<?php

namespace App\Repository\Novasoft\Report;

use App\Entity\Novasoft\Report\CertificadoLaboral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CertificadoLaboral|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoLaboral|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoLaboral[]    findAll()
 * @method CertificadoLaboral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificadoLaboralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CertificadoLaboral::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param CertificadoLaboral $entity
     * @return CertificadoLaboral|null
     */
    public function findEqual(CertificadoLaboral $entity)
    {
        $qb = $this->createQueryBuilder('cl')
            ->andWhere('cl.usuario = :usuario')
            ->andWhere('cl.fechaIngreso = :fechaIngreso')
            ->andWhere('cl.empresaUsuaria = :empresaUsuaria')
            ->andWhere('cl.cargo = :cargo')
            ->setParameter('usuario', $entity->getUsuario())
            ->setParameter('fechaIngreso', $entity->getFechaIngreso()->format('Y-m-d'))
            ->setParameter('empresaUsuaria', $entity->getEmpresaUsuaria())
            ->setParameter('cargo', $entity->getCargo())
            ->setMaxResults(1);
        if($entity->getFechaEgreso()) {
            $qb->andWhere('cl.fechaEgreso = :fechaEgreso')
                ->setParameter('fechaEgreso', $entity->getFechaEgreso()->format('Y-m-d'));
        } else {
            $qb->andWhere($qb->expr()->isNull('cl.fechaEgreso'));
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
