<?php

namespace App\Repository\Main;

use App\Entity\Main\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
    private $usuariosCached = [];


    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function findByIdentificacionCached($identificacion)
    {
        if(!isset($this->usuariosCached[$identificacion])) {
            $this->usuariosCached[$identificacion] = $this->findByIdentificacion($identificacion);
        }
        return $this->usuariosCached[$identificacion];
    }

    /**
     * @param $identificacion
     * @return Usuario|null
     */
    public function findByIdentificacion($identificacion)
    {
        try {
            return $this->createQueryBuilder('u')
                ->andWhere('u.identificacion = :identificacion')
                ->setParameter('identificacion', $identificacion)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @return Usuario|null
     * @throws NonUniqueResultException
     */
    public function superAdmin()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere($qb->expr()->like('u.roles', "'%ROLE_SUPERADMIN%'"));
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param string|array $rol
     * @param null|string|array $idOrIdent
     * @return Usuario[]|Usuario|null
     */
    public function findByRol($rol, $idOrIdent = null)
    {
        $query = $this->findByRolQuery($rol, $idOrIdent);
        return $idOrIdent && !is_array($idOrIdent) ? $query->getOneOrNullResult() : $query->getResult();
    }

    /**
     * @param $rol
     * @param null $idOrIdent
     * @return Query
     */
    public function findByRolQuery($rol, $idOrIdent = null)
    {
        $qb = $this->findByRolQueryBuilder($rol, $idOrIdent);
        return $qb->getQuery();
    }

    public function countByRolQuery($rol, $idOrIdent = null)
    {
        $qb = $this->findByRolQueryBuilder($rol, $idOrIdent);
        return $qb->select('COUNT(u.id)')->getQuery();
    }

    public function findIdentsByRol($rol, $idOrIdent = null)
    {
        $qb = $this->findByRolQueryBuilder($rol, $idOrIdent)
            ->select('u.identificacion');
        return $qb->getQuery()->getResult('FETCH_COLUMN');
    }

    public function countIdsByRol($rol, $idOrIdent = null)
    {
        $qb = $this->findByRolQueryBuilder($rol, $idOrIdent)
            ->select('COUNT(u.id)');
        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Usuario[]
     */
    public function findEmpleados()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere($qb->expr()->like('u.roles', "'%ROLE_EMPLEADO%'"));
        return $qb->getQuery()->getResult();
    }

    /**
     * @return string[]
     */
    public function findEmpleadosIdents($exceptIds = [])
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u.identificacion')->andWhere($qb->expr()->like('u.roles', "'%ROLE_EMPLEADO%'"))->orderBy('u.id', 'ASC');

        if($exceptIds) {
            $qb->andWhere($qb->expr()->notIn('u.id', $exceptIds));
        }

        return $qb->getQuery()->getResult('FETCH_COLUMN');
    }

    /**
     * @param string $term
     * @param string|string[]|null $rol
     * @return mixed
     */
    public function search(string $term, $rol = null)
    {
        $qb = $this->createQueryBuilder('u');
        if($rol) {
            $this->filterRol($rol, $qb);
        }
        return $qb
            ->andWhere($this->userSearchExpressionStrict($qb, $term))
            ->getQuery()
            ->getResult();
    }


    public function userSearchExpression(QueryBuilder $qb, $search, $alias = 'u', $parameterKey = 'search')
    {
        $expr = $qb->expr()->orX(
            $this->userSearchExpressionIdentificacion($qb, $alias, $parameterKey),
            $this->userSearchExpressionNombres($qb, $alias, $parameterKey)
        );
        $qb->setParameter($parameterKey, "%" . str_replace(" ", "%", $search) . "%");
        return $expr;
    }

    public function userSearchExpressionStrict(QueryBuilder $qb, $search, $alias = 'u', $parameterKey = 'search')
    {
        $qb->setParameter($parameterKey, "%" . str_replace(" ", "%", $search) . "%");
        return is_numeric($search)
            ? $this->userSearchExpressionIdentificacion($qb, $alias, $parameterKey)
            : $this->userSearchExpressionNombres($qb, $alias, $parameterKey);
    }

    protected function userSearchExpressionIdentificacion(QueryBuilder $qb, $alias = 'u', $parameterKey = 'search')
    {
        return $qb->expr()->like($alias . '.identificacion', ':'.$parameterKey);
    }

    protected function userSearchExpressionNombres(QueryBuilder $qb, $alias = 'u', $parameterKey = 'search')
    {
        return $qb->expr()->like(
            $qb->expr()->concat(
                $qb->expr()->concat(
                    $qb->expr()->concat(
                        $alias.'.primerNombre',
                        'COALESCE(' . $qb->expr()->concat($qb->expr()->literal(' '), $alias.'.segundoNombre') . ', \'\')'
                    ),
                    $qb->expr()->literal(' ')
                ),
                $qb->expr()->concat(
                    $alias.'.primerApellido',
                    'COALESCE(' . $qb->expr()->concat($qb->expr()->literal(' '), $alias.'.segundoApellido') . ', \'\')'
                )
            ),
            ':'.$parameterKey
        );
    }

    protected function findByRolQueryBuilder($rol, $idOrIdent = null)
    {
        $qb = $this->filterRol($rol);
        if($idOrIdent) {
            if(!is_array($idOrIdent)) {
                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->eq('u.id', ':idOrIdent'),
                    $qb->expr()->eq('u.identificacion', ':idOrIdent')
                ))
                    ->setParameter('idOrIdent', $idOrIdent)
                    ->setMaxResults(1);
            } else {
                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->in('u.id', $idOrIdent),
                    $qb->expr()->in('u.identificacion', $idOrIdent)
                ));
            }
        }
        return $qb;
    }

    /**
     * @param string|string[] $rol
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    protected function filterRol($rol, ?QueryBuilder $qb = null)
    {
        $qb = !$qb ? $this->createQueryBuilder('u') : $qb;

        if(!is_array($rol)) {
            $qb->andWhere($qb->expr()->like('u.roles', "'%$rol%'"));
        } else {
            $qb->andWhere(call_user_func_array([$qb->expr(), 'orX'], array_map(function ($rol) use($qb) {
                return $qb->expr()->like('u.roles', "'%$rol%'");
            }, $rol)));
        }
        return $qb;
    }
}
