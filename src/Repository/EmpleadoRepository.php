<?php

namespace App\Repository;

use App\Entity\Empleado;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use phpDocumentor\Reflection\Types\Static_;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Empleado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empleado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empleado[]    findAll()
 * @method Empleado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Empleado::class);
    }

    /**
     * @param string|string[] $identificacion
     * @return Empleado|null|Empleado[]
     */
    public function findByIdentificacion($identificacion)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.usuario', 'u');

        if(is_array($identificacion)) {
            $qb->andWhere($qb->expr()->in('u.identificacion', ':identificacion'));
        }else{
            $qb->andWhere('u.identificacion = :identificacion');
        }

        $query = $qb->setParameter('identificacion', $identificacion)
            ->getQuery();

        return is_array($identificacion) ? $query->getResult() : $query->getOneOrNullResult();
    }

    /**
     * @param string $email
     * @return Usuario|null
     */
    public function findByEmail($email)
    {
        try {
            return $this->createQueryBuilder('e')
                ->join('e.usuario', 'u')
                ->andWhere('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @return Usuario[]
     */
    public function findAllUsuarios()
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('u')
            ->from(Usuario::class, 'u')
            ->join($this->_entityName, 'e', 'WITH', 'u = e.usuario');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param string|string[]|null $codigoConvenio
     * @return Empleado[]
     */
    public function findByConvenio($codigoConvenio = null)
    {
        $qb = $this->createQueryBuilder('e');

        if($codigoConvenio) {
            $qb->join('e.convenio', 'c');
            if (is_array($codigoConvenio)) {
                $qb->andWhere($qb->expr()->in('c.codigo', ':codigoConvenio'));
            } else {
                $qb->andWhere('c.codigo = :codigoConvenio');
            }
            $qb->setParameter('codigoConvenio', $codigoConvenio);
        }
        return $qb->getQuery()->getResult();
    }

    public function findBySsrsDb($ssrsDb)
    {
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.ssrsDb', ':ssrsDb')
            ->setParameter('ssrsDb', $ssrsDb);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $periodoInicio
     * @param $periodoFin
     * @param null $codigoConvenio
     * @return mixed
     * @throws QueryException
     */
    public function findByRangoPeriodo($periodoInicio, $periodoFin, $codigoConvenio = null)
    {
        $qb = $this->createQueryBuilder('e')
            ->addCriteria(static::rangoPeriodoCriteria($periodoInicio, $periodoFin));
        if($codigoConvenio) {
            $qb->join('e.convenio', 'c')
                ->addCriteria(static::convenioCriteria($codigoConvenio));
        }
        return $qb->getQuery()->getResult();
    }

    public static function rangoPeriodoCriteria($periodoInicio, $periodoFin)
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->andX(
                Criteria::expr()->lt('fechaIngreso', $periodoFin),
                Criteria::expr()->orX(
                    Criteria::expr()->isNull('fechaRetiro'),
                    Criteria::expr()->gt('fechaRetiro', $periodoInicio)
                )
            ));
    }



    /**
     * @param $periodoInicio
     * @param $periodoFin
     * @param string[]|int[] $search por identificaciones o codigo convenios
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws QueryException
     */
    public function countByRango($periodoInicio, $periodoFin, $search = [])
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->select($qb->expr()->count('e'))
            ->addCriteria(static::rangoPeriodoCriteria($periodoInicio, $periodoFin));

        if($search) {
            if(is_numeric($search[0])) {
                $qb->join('e.usuario', 'u')
                    ->andWhere($qb->expr()->in('u.identificacion', $search));
            } else {
                $qb->andWhere($qb->expr()->in('e.convenio', $search));
            }
        }

        return (int) $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * @return Empleado[]
     */
    public function findWithoutConvenio()
    {
        $qb = $this->createQueryBuilder('e');
        return $qb
            ->join('e.usuario', 'u')
            ->where($qb->expr()->isNull('e.convenio'))
            ->getQuery()
            ->getResult();
    }

    /**
     * @return string[]
     */
    public function findIdentsWithoutConvenio()
    {
        $qb = $this->createQueryBuilder('e');
        return $qb
            ->select('u.identificacion')
            ->join('e.usuario', 'u')
            ->where($qb->expr()->isNull('e.convenio'))
            ->getQuery()
            ->getResult('FETCH_COLUMN');
    }


    public function getUsuariosIds()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('u.id')
            ->join('e.usuario', 'u');
        return $qb->getQuery()->getResult('FETCH_COLUMN');
    }

    public static function convenioCriteria($codigos) {
        $criteria = Criteria::create();
        if(is_array($codigos)) {
            $criteria->andWhere(Criteria::expr()->in('c.codigo', $codigos));
        } else {
            $criteria->andWhere(Criteria::expr()->eq('c.codigo', $codigos));
        }
        return $criteria;
    }
}
