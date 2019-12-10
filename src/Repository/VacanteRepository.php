<?php

namespace App\Repository;

use App\Entity\Hv;
use App\Entity\Vacante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vacante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacante[]    findAll()
 * @method Vacante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacanteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vacante::class);
    }

    /**
     * @return Vacante[]
     */
    public function findPublicada($search = null, $categoria = null, $categoriaId = null)
    {
        $qb = $this->createQueryBuilder('v')
            ->andWhere('v.publicada = true')
            ->join('v.ciudad', 'ciudad')
            ->addSelect('ciudad')
            ->orderBy('v.createdAt', 'DESC');

        if($search) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('v.titulo', ':search'),
                    $qb->expr()->like('v.descripcion', ':search'),
                    $qb->expr()->like('ciudad.nombre', ':search')
                )
            )->setParameter('search', '%' . $search . '%');
        }
        if($categoria && $categoriaId) {
            $qb->join('v.' . $categoria, 'c', 'WITH', 'c.id = :categoriaId')
                ->setParameter('categoriaId', $categoriaId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Vacante[]
     */
    public function findActivas()
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.activa = true')
            ->getQuery()
            ->getResult();
    }

    public function getCategoriaPublicada($categoria)
    {
        return $this->createQueryBuilder('v')
            ->select('c.id, c.nombre, COUNT(v.id) as count')
            ->andWhere('v.publicada = true')
            ->join('v.'.$categoria, 'c')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }

    public static function hvCriteria(Hv $hv): Criteria
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->andX(
                Criteria::expr()->eq('id', $hv->getId())
            ));
        return $criteria;
    }
}
