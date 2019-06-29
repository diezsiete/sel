<?php

namespace App\Repository;

use App\Entity\Hv;
use App\Entity\Usuario;
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

    public function search($search = null)
    {
        $qb = $this->createQueryBuilder('hv')
            ->orderBy('hv.id', 'DESC')
            ->addSelect('usuario, resiCiudad, estudio, estudioCodigo, experiencia, experienciaArea, adjunto')
            ->join('hv.usuario', 'usuario')
            ->join('hv.resiCiudad', 'resiCiudad')
            ->join('hv.estudios', 'estudio')
            ->join('estudio.codigo', 'estudioCodigo')
            ->join('hv.experiencia', 'experiencia')
            ->join('experiencia.area', 'experienciaArea')
            ->leftJoin('hv.adjunto', 'adjunto')
            ->setFirstResult(0)
            ->setMaxResults(10);

        $paginator = new Paginator($qb->getQuery(), true);
        $result = [];
        foreach ($paginator as $post) {
            $result[] = $post;
        }
        return $result;

    }

    public function searchQueryBuilder($search = null)
    {
        $qb = $this->createQueryBuilder('hv')
            ->orderBy('hv.id', 'DESC')
            ->addSelect('usuario, resiCiudad, estudio, estudioCodigo, experiencia, experienciaArea, adjunto')
            ->join('hv.usuario', 'usuario')
            ->join('hv.resiCiudad', 'resiCiudad')
            ->join('hv.estudios', 'estudio')
            ->join('estudio.codigo', 'estudioCodigo')
            ->join('hv.experiencia', 'experiencia')
            ->join('experiencia.area', 'experienciaArea')
            ->leftJoin('hv.adjunto', 'adjunto');

        if($search) {
            $qb->andWhere(
                $qb->expr()->orX(
                $this->usuarioRepository->userSearchExpression($qb, $search, 'usuario', 'search_usuario'),
                $this->searchExpression($qb)
                )
            )->setParameter('search', '%' . $search . '%');
        }

        return $qb;
    }

    public function searchExpression(QueryBuilder $qb)
    {
        $expr = $qb->expr()->orX(
            $qb->expr()->like('estudio.nombre', ':search'),
            $qb->expr()->like('experiencia.empresa', ':search'),
            $qb->expr()->like('experiencia.cargo', ':search'),
            $qb->expr()->like('experiencia.descripcion', ':search')
        );
        return $expr;
    }



    // /**
    //  * @return Hv[] Returns an array of Hv objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hv
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
