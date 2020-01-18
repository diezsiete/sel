<?php

namespace App\Repository\Novasoft\Report;

use App\Entity\Novasoft\Report\LiquidacionContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LiquidacionContrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidacionContrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidacionContrato[]    findAll()
 * @method LiquidacionContrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidacionContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LiquidacionContrato::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param LiquidacionContrato $entity
     * @return mixed
     */
    public function findEqual(LiquidacionContrato $entity)
    {
        return $this->createQueryBuilder('lc')
            ->andWhere('lc.usuario = :usuario')
            ->andWhere('lc.numeroContrato = :numeroContrato')
            ->andWhere('lc.fechaIngreso = :fechaIngreso')
            ->andWhere('lc.fechaRetiro = :fechaRetiro')
            ->setParameter('usuario', $entity->getUsuario())
            ->setParameter('numeroContrato', $entity->getNumeroContrato())
            ->setParameter('fechaIngreso', $entity->getFechaIngreso()->format('Y-m-d'))
            ->setParameter('fechaRetiro', $entity->getFechaRetiro()->format('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
