<?php

namespace App\Repository\Hv;

use App\Entity\Hv\Hv;
use App\Entity\Main\Usuario;
use App\Repository\Main\UsuarioRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Hv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hv[]    findAll()
 * @method Hv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HvRepository extends ServiceEntityRepository
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    public function __construct(RegistryInterface $registry, UsuarioRepository $usuarioRepository)
    {
        parent::__construct($registry, Hv::class);
        $this->usuarioRepository = $usuarioRepository;
    }

    public function findByUsuario($usuario)
    {
        return $this->findOneBy(['usuario' => $usuario]);
    }

    public function searchQueryBuilder($search = null)
    {
        $qb = $this->createQueryBuilder('hv');
        $this->searchQueryBuilderFields($qb);

        if($search) {
            $this->searchExpression($qb, $search);
        }

        return $qb;
    }

    public function searchExpression(QueryBuilder $qb, $search)
    {
        $expr = $qb->expr()->orX(
            $qb->expr()->like('estudio.nombre', ':search'),
            $qb->expr()->like('experiencia.empresa', ':search'),
            $qb->expr()->like('experiencia.cargo', ':search'),
            $qb->expr()->like('experiencia.descripcion', ':search')
        );

        $qb->andWhere(
            $qb->expr()->orX(
                $this->usuarioRepository->userSearchExpression($qb, $search, 'usuario', 'search_usuario'),
                $expr
            )
        )->setParameter('search', '%' . $search . '%');
    }


    public function searchQueryBuilderFields(QueryBuilder $qb, $alias = 'hv')
    {
        $joinsDef = [
            'usuario' => ['join' => $alias . '.usuario', 'alias' => 'usuario'],
            'resiCiudad' => ['join' => $alias. '.resiCiudad', 'alias' => 'resiCiudad', 'type' => 'left'],
            'estudio' => ['join' => $alias. '.estudios', 'alias' => 'estudio', 'type' => 'left'],
            //'estudioCodigo' => ['join' => "estudio.codigo", 'alias' => 'estudioCodigo'],
            'experiencia' => ['join' => $alias. '.experiencias', 'alias' => 'experiencia', 'type' => 'left'],
            //'experienciaArea' => ['join' => 'experiencia.area', 'alias' => 'experienciaArea'],
            //'adjunto' => ['join' => $alias.".adjunto", 'alias' => 'adjunto', 'type' => 'left']
        ];
        $select = implode(', ', array_keys($joinsDef));
        $qb->addSelect($select);
        foreach($joinsDef as $join) {
            $type = $join['type'] ?? 'inner';
            $qb->{$type . 'Join'}($join['join'], $join['alias']);
        }
        $qb->orderBy($alias . '.id', 'DESC');
        $qb->groupBy("$alias.id");
        /*$qb
            ->addSelect('usuario, resiCiudad, estudio, estudioCodigo, experiencia, experienciaArea, adjunto')
            ->join($alias.".usuario", 'usuario')
            ->join($alias.".resiCiudad", 'resiCiudad')
            ->join($alias.".estudios", 'estudio')
            ->join("estudio.codigo", 'estudioCodigo')
            ->join($alias.".experiencia", 'experiencia')
            ->join('experiencia.area', 'experienciaArea')
            ->leftJoin($alias.".adjunto", 'adjunto')
            ->orderBy($alias.".id", 'DESC');*/

        return $qb;
    }


    public function findByIdentificacion($value): ?Hv
    {
        return $this->createQueryBuilder('hv')
            ->join('hv.usuario', 'u')
            ->andWhere('u.identificacion = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
