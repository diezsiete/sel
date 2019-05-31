<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
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
            $this->usuariosCached[$identificacion] = $this->createQueryBuilder('u')
                ->andWhere('u.identificacion = :identificacion')
                ->setParameter('identificacion', $identificacion)
                ->getQuery()
                ->getOneOrNullResult();
        }
        return $this->usuariosCached[$identificacion];
    }


    /*
    public function findOneBySomeField($value): ?Usuario
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function userSearch(QueryBuilder $qb, $search, $alias = 'u')
    {
        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->like($alias . '.identificacion', ':search'),
            $qb->expr()->like(
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
                        'COALESCE(' . $qb->expr()->concat($qb->expr()->literal(' '), $alias.'.segundoNombre') . ', \'\')'
                    )
                ),
                ':search'
            )
        ))->setParameter('search', "%" . str_replace(" ", "%", $search) . "%");
    }
}
