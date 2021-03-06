<?php

namespace App\Repository\Autoliquidacion;

use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Main\Empleado;
use App\Entity\Main\Representante;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AutoliquidacionEmpleado|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutoliquidacionEmpleado|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutoliquidacionEmpleado[]    findAll()
 * @method AutoliquidacionEmpleado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoliquidacionEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutoliquidacionEmpleado::class);
    }

    public function findByConvenio($convenio, ?DateTimeInterface $periodo = null, $code = false)
    {
        $qb = $this->findByConvenioQuery($convenio, $periodo, $code);
        return $qb->getQuery()->getResult();
    }

    public function findByIdentificaciones(?DateTimeInterface $periodo = null, $identsFilter = [], $code = false)
    {
        $qb = $this->findByIdentificacionesQuery($periodo, $identsFilter, $code);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Empleado $empleado
     * @param DateTimeInterface $periodo
     * @return null|AutoliquidacionEmpleado|AutoliquidacionEmpleado[]
     */
    public function findByEmpleadoPeriodo(Empleado $empleado, ?DateTimeInterface $periodo = null)
    {
        $qb = $this->createQueryBuilder('ae')
            ->join('ae.autoliquidacion', 'a')
            ->andWhere('ae.empleado = :empleado')
            ->setParameter('empleado', $empleado);
        if($periodo) {
           $qb->andWhere('a.periodo = :periodo')
               ->setParameter('periodo', $periodo);
        }
        try {
            return $periodo
                ? $qb->getQuery()->getOneOrNullResult()
                : $qb->getQuery()->getResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param string $ident
     * @param DateTimeInterface|null $periodo
     * @return AutoliquidacionEmpleado[]|AutoliquidacionEmpleado
     * @throws NonUniqueResultException
     */
    public function findByIdentPeriodo(string $ident, ?DateTimeInterface $periodo = null)
    {
        $qb = $this->createQueryBuilder('ae')
            ->join('ae.empleado', 'e')
            ->join('e.usuario', 'u')
            ->andWhere('u.identificacion = :ident')
            ->setParameter('ident', $ident);
        if($periodo) {
            $qb->join('ae.autoliquidacion', 'a')
                ->andWhere('a.periodo = :periodo')
                ->setParameter('periodo', $periodo->format('Y-m-d'));
            return $qb->getQuery()->getOneOrNullResult();
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param \App\Entity\Main\Representante $representante
     * @param Autoliquidacion|DateTimeInterface|null $filter
     * @return AutoliquidacionEmpleado[]
     */
    public function findByRepresentante(Representante $representante, $filter = null)
    {
        $qb = $this->createQueryBuilder('ae')
            ->join('ae.empleado', 'e')
            ->andWhere('e.representante = :representante')
            ->setParameter('representante', $representante);
        if($filter) {
            if($filter instanceof Autoliquidacion) {
                $qb->andWhere('ae.autoliquidacion = :autoliquidacion')
                    ->setParameter('autoliquidacion', $filter);
            } else {
                $qb->join('ae.autoliquidacion', 'a')
                    ->andWhere('a.periodo = :periodo')
                    ->setParameter('periodo', $filter->format('Y-m-d'));
            }
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Autoliquidacion $autoliquidacion
     * @return AutoliquidacionEmpleado[]
     */
    public function findByAutoliquidacion(Autoliquidacion $autoliquidacion)
    {
        return $this->createQueryBuilder('ae')
            ->addSelect('e, u')
            ->join('ae.empleado', 'e')
            ->join('e.usuario', 'u')
            ->andWhere('ae.autoliquidacion = :autoliquidacion')
            ->setParameter('autoliquidacion', $autoliquidacion)
            ->getQuery()
            ->getResult();
    }


    protected function findByConvenioQuery($convenio, ?DateTimeInterface $periodo = null, $code = false)
    {
        $qb = $this->createQueryBuilder('ae')
            ->join('ae.autoliquidacion', 'a');
        if(is_array($convenio)) {
            $qb->andWhere($qb->expr()->in('a.convenio', $convenio));
        }else{
            $qb->andWhere($qb->expr()->eq('a.convenio', $convenio));
        }
        if($periodo) {
            $qb->addCriteria(AutoliquidacionRepository::periodoCriteria($periodo, 'a'));
        }
        $qb->addCriteria(static::codeCriteria($code));
        return $qb;
    }

    protected function findByIdentificacionesQuery(?DateTimeInterface $periodo = null, $identsFilter = [], $code = false)
    {
        $qb = $this
            ->createQueryBuilder('ae')
            ->join('ae.autoliquidacion', 'a');
        if($periodo) {
            $qb->addCriteria(AutoliquidacionRepository::periodoCriteria($periodo, 'a'));
        }
        if($identsFilter) {
            $qb->join('ae.empleado', 'e')
                ->join('e.usuario', 'u')
                ->andWhere($qb->expr()->in('u.identificacion', $identsFilter));
        }
        $qb->addCriteria(static::codeCriteria($code));
        return $qb;
    }

    public static function codeCriteria($code = false)
    {
        $criteria = Criteria::create();
        if($code === null) {
            $criteria->andWhere($criteria->expr()->orX($criteria->expr()->isNull('ae.code')));
        }
        else if($code !== false) {
            $expr = preg_match('/^!(.+)/', $code, $matches)
                ? $criteria->expr()->neq('ae.code', $matches[1])
                : $criteria->expr()->eq('ae.code', $code);
            $criteria->andWhere($expr);
        }
        return $criteria;
    }
}
