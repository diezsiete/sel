<?php

namespace App\Repository\ServicioEmpleados;

use App\Entity\ServicioEmpleados\Nomina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nomina|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nomina|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nomina[]    findAll()
 * @method Nomina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NominaRepository extends ReportRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nomina::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param Nomina $nomina
     * @return Nomina|null
     */
    public function findEqual(Nomina $nomina)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.source = :source')
            ->andWhere('n.fecha = :fecha')
            ->andWhere('n.usuario = :usuario')
            ->andWhere('n.sourceId = :sourceId')
            ->setParameters([
                'source'   => $nomina->getSource(),
                'fecha'    => $nomina->getFecha()->format('Y-m-d'),
                'usuario'  => $nomina->getUsuario(),
                'sourceId' => $nomina->getSourceId()
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
