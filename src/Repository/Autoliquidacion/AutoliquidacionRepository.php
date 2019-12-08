<?php

namespace App\Repository\Autoliquidacion;

use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Empleado;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Autoliquidacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autoliquidacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autoliquidacion[]    findAll()
 * @method Autoliquidacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoliquidacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Autoliquidacion::class);
    }

    /**
     * @param Autoliquidacion|Autoliquidacion[] $autoliquidacion
     * @return string[]
     */
    public function getEmpleadosIdentificaciones($autoliquidacion)
    {
        $qb = $this->createQueryBuilder('a');

        if(is_object($autoliquidacion)) {
            $qb
                ->andWhere('a = :autoliquidacion')
                ->setParameter('autoliquidacion', $autoliquidacion);
        } else {
            $qb->andWhere($qb->expr()->in('a', $autoliquidacion));
        }

        return $this->fetchIdentificaciones($qb);
    }


    /**
     * @param string[] $convenio
     * @param DateTimeInterface $periodo
     * @param bool $noExito
     * @return string[]
     * @throws QueryException
     */
    public function getIdentificacionesByConvenio($convenio, DateTimeInterface $periodo, $overwrite = false)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->join('a.convenio', 'c')
            ->andWhere($qb->expr()->in('c.codigo', $convenio))
            ->addCriteria(static::periodoCriteria($periodo));
        return $this->fetchIdentificaciones($qb, $overwrite);
    }

    /**
     * @param DateTimeInterface|null $periodo
     * @return Autoliquidacion[]
     * @throws QueryException
     */
    public function findByPeriodo(?DateTimeInterface $periodo = null)
    {
        $qb = $this->createQueryBuilder('a');
        if($periodo) {
            $qb->addCriteria(static::periodoCriteria($periodo));
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param string|string[] $convenio codigo convenio
     * @param DateTimeInterface|null $periodo
     * @return Autoliquidacion[]
     * @throws QueryException
     */
    public function findByConvenio($convenio, ?DateTimeInterface $periodo = null)
    {
        $qb = $this->createQueryBuilder('a');
        if(is_array($convenio)) {
            $qb->andWhere($qb->expr()->in('a.convenio', $convenio));
        }else{
            $qb->andWhere($qb->expr()->eq('a.convenio', $convenio));
        }
        if($periodo) {
            $qb->addCriteria(static::periodoCriteria($periodo));
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Empleado $empleado
     * @param DateTimeInterface|null $periodo
     * @return Autoliquidacion|Autoliquidacion[]
     */
    public function findByEmpleado($empleado, ?DateTimeInterface $periodo = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.empleados', 'ae')
            ->where('ae.empleado = :empleado')
            ->setParameter('empleado', $empleado);

        if($periodo) {
            $qb->andWhere('a.periodo = :periodo')
                ->setParameter('periodo', $periodo->format('Y-m-d'));
            try {
                return $qb->getQuery()->getOneOrNullResult();
            } catch (NonUniqueResultException $e) {
                return null;
            }
        } else {
            return $qb->getQuery()->getResult();
        }
    }

    /**
     * @param $identificacion
     * @param DateTimeInterface|null $periodo
     * @return Autoliquidacion[]|Autoliquidacion|null
     * @throws QueryException
     */
    public function findByIdentificacion($identificacion, ?DateTimeInterface $periodo = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.empleados', 'ae')
            ->join('ae.empleado', 'e')
            ->join('e.usuario', 'u');

        if(is_array($identificacion)) {
            $qb->andWhere($qb->expr()->in('u.identificacion', $identificacion));
        }else{
            $qb->andWhere($qb->expr()->eq('u.identificacion', $identificacion));
        }

        if($periodo) {
            $qb->addCriteria(static::periodoCriteria($periodo));
            if(!is_array($identificacion)) {
                try {
                    return $qb->getQuery()->getOneOrNullResult();
                } catch (NonUniqueResultException $e) {
                    return null;
                }
            }
        }

        return $qb->getQuery()->getResult();
    }


    public static function periodoCriteria(DateTimeInterface $periodo, $alias = "")
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq(($alias ? "$alias." : "") . 'periodo', $periodo->format('Y-m-d')));

    }

    /**
     * @param QueryBuilder $qb
     * @return string[]
     */
    protected function fetchIdentificaciones(QueryBuilder $qb, $overwrite = false)
    {
        $qb = $qb->select('u.identificacion')
            ->join('a.empleados', 'ae')
            ->join('ae.empleado', 'e')
            ->join('e.usuario', 'u');

        if(!$overwrite) {
            $qb->andWhere($qb->expr()->eq('ae.exito', $qb->expr()->literal(false)));
        }
        else if($overwrite !== true) {
            $expr = preg_match('/^!(.+)/', $overwrite, $matches)
                ? $qb->expr()->neq('ae.code', $matches[1])
                : $qb->expr()->eq('ae.code', $overwrite);
            $qb->andWhere($expr);
        }

        return $qb->getQuery()->getResult('FETCH_COLUMN');
    }
}
