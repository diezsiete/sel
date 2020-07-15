<?php

namespace App\Repository\Scraper;

use App\Entity\Scraper\Solicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use ReflectionClass;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Solicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method Solicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method Solicitud[]    findAll()
 * @method Solicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudRepository extends ServiceEntityRepository
{
    const SIN_EJECUTAR = 0;
    const EJECUTANDO = 1;
    const EJECUTADO_EXITO = 2;
    const EJECUTADO_ERROR = 3;
    const ESPERANDO_EN_COLA = 4;

    public function getEstadoString($estado) {
        switch($estado) {
            case static::SIN_EJECUTAR:
                return 'SIN EJECUTAR';
            case static::EJECUTANDO:
                return 'EJECUTANDO';
            case static::EJECUTADO_EXITO:
                return 'EJECUTADO EXITO';
            case static::ESPERANDO_EN_COLA:
                return 'ESPERANDO EN COLA';
            default:
                return 'EJECUTADO ERROR';
        }
    }

    public function getEstadoArray()
    {
        return array_flip((new ReflectionClass($this))->getConstants());
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Solicitud::class);
    }

    /**
     * @param int|array $hvId
     * @return Solicitud[]
     * @throws NonUniqueResultException
     */
    public function findLastSolicitud($hvId)
    {
        if(is_array($hvId)) {
            $qb = $this->createQueryBuilder('s')
                ->addSelect('hv')
                ->join('s.hv', 'hv');

            $qb->where(
                $qb->expr()->in('s.id',
                    $this
                        ->createQueryBuilder('s2')
                        ->select('MAX(s2.id)')
                        ->join('s2.hv', 'hv2')
                        ->andWhere($qb->expr()->in('hv2.id', $hvId))
                        ->groupBy('hv2.id')
                        ->getDQL()
                )
            );

            return $qb->getQuery()->getResult();
        } else {
            // TODO probar que funciona
            $qb = $this->createQueryBuilder('s')
                ->addSelect('hv')
                ->join('s.hv', 'hv');
            return $qb
                ->where($qb->expr()->eq('hv.id', $hvId))
                ->orderBy('s.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
    }

    /**
     * @param int $hvId
     * @return Solicitud[]
     */
    public function findFailedByHvId(int $hvId)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->join('s.hv', 'hv')
            ->andWhere($qb->expr()->eq('hv.id', $hvId))
            ->andWhere($qb->expr()->neq('s.estado', static::EJECUTADO_EXITO));
        return $qb->getQuery()->getResult();
    }


}
