<?php

namespace App\Repository\Novasoft\Report\Clientes;

use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListadoNomina|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListadoNomina|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListadoNomina[]    findAll()
 * @method ListadoNomina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListadoNominaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListadoNomina::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param ListadoNomina $entity
     * @return ListadoNomina|null
     */
    public function findEqual(ListadoNomina $entity)
    {
        return $this->createQueryBuilder('ln')
            ->andWhere('ln.convenio = :convenio')
            ->andWhere('ln.fechaNomina = :fecha')
            ->andWhere('ln.tipoLiquidacion = :tipo')
            ->setParameters([
                'convenio' => $entity->getConvenio(),
                'fecha' => $entity->getFechaNomina()->format('Y-m-d'),
                'tipo' => $entity->getTipoLiquidacion()
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
