<?php

namespace App\Repository\Scraper;

use App\Entity\Scraper\Solicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use ReflectionClass;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

    public function __construct(RegistryInterface $registry)
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
        $qb = $this->createQueryBuilder('s')
            ->addSelect('hv')
            ->join('s.hv', 'hv');
        if(is_array($hvId)) {
            $qb->andWhere($qb->expr()->in('hv.id', $hvId));
        } else {
            $qb->andWhere($qb->expr()->eq('hv.id', $hvId));
        }
        $qb->orderBy('s.createdAt', 'DESC')
            ->groupBy('s.hv');

        if(is_array($hvId)) {
            return $qb->getQuery()->getResult();
        } else {
            return $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
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
            ->andWhere($qb->expr()->eq('hv.id', $hvId));
        return $qb->getQuery()->getResult();
    }


}
